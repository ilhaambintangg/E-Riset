<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Submission;
use App\Models\Panitera;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionStatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $panitera;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->panitera = Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera',
            'nip' => '196102181987031002',
            'status_aktif' => true,
        ]);
    }

    private function createSubmission($status)
    {
        return Submission::create([
            'registration_number' => 'ERS-2026-000001',
            'name' => 'John Doe',
            'nim' => '12345',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'john@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum',
            'target_institution' => 'PN Jakarta Pusat',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-123',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum',
            'research_location' => 'Pengadilan Negeri Jakarta Pusat',
            'research_type' => 'Skripsi',
            'current_status' => $status
        ]);
    }

    public function test_transition_from_pending_to_processing_is_allowed()
    {
        $submission = $this->createSubmission('Menunggu Verifikasi');

        // Prepare dummy templates inside storage for letter generator to not throw exception
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_individua_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $placeholders = [
            'nomor_surat', 'tanggal_surat', 'jabatan_tujuan', 'universitas', 'kota_tujuan',
            'nomor_surat_pengantar', 'tanggal_surat_pengantar', 'semester', 'fakultas',
            'program_studi', 'lokasi_penelitian', 'judul_penelitian', 'tujuan_penelitian',
            'nama_panitera', 'jabatan_panitera', 'nip_panitera', 'nama', 'npm'
        ];
        foreach ($placeholders as $p) {
            $section->addText('${' . $p . '}');
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        \App\Models\TemplateSurat::create([
            'name' => $tempFile,
            'institution_type' => 'PN',
            'template_type' => 'individu',
            'file_path' => 'templates/' . $tempFile,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Sedang Diproses',
            'panitera_id' => $this->panitera->id,
            'letter_date' => '2026-06-25',
        ]);

        // clean up template
        @unlink($tempPath);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Sedang Diproses', $submission->fresh()->current_status);
    }

    public function test_transition_from_pending_to_rejected_is_allowed()
    {
        $submission = $this->createSubmission('Menunggu Verifikasi');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Ditolak',
            'notes' => 'Proposal tidak relevan.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Ditolak', $submission->fresh()->current_status);
    }

    public function test_transition_from_pending_to_approved_is_forbidden()
    {
        $submission = $this->createSubmission('Menunggu Verifikasi');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Disetujui',
        ]);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals('Menunggu Verifikasi', $submission->fresh()->current_status);
    }

    public function test_transition_from_processing_to_approved_is_allowed()
    {
        $submission = $this->createSubmission('Sedang Diproses');

        $file = \Illuminate\Http\UploadedFile::fake()->create('permit.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Disetujui',
            'permit_file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Disetujui', $submission->fresh()->current_status);
    }

    public function test_transition_from_processing_to_pending_is_forbidden()
    {
        $submission = $this->createSubmission('Sedang Diproses');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Menunggu Verifikasi',
        ]);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals('Sedang Diproses', $submission->fresh()->current_status);
    }

    public function test_transition_from_approved_to_any_is_forbidden()
    {
        $submission = $this->createSubmission('Disetujui');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Sedang Diproses',
        ]);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals('Disetujui', $submission->fresh()->current_status);
    }

    public function test_transition_from_rejected_to_any_is_forbidden()
    {
        $submission = $this->createSubmission('Ditolak');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Sedang Diproses',
        ]);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals('Ditolak', $submission->fresh()->current_status);
    }

    public function test_transition_from_processing_to_rejected_with_file_saves_file_and_status()
    {
        $submission = $this->createSubmission('Sedang Diproses');
        $file = \Illuminate\Http\UploadedFile::fake()->create('rejection.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Ditolak',
            'notes' => 'Tolak dengan surat balasan penolakan resmi.',
            'permit_file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Ditolak', $submission->fresh()->current_status);
        $this->assertNotNull($submission->fresh()->permit_file_path);
    }

    public function test_permit_file_size_validation_limit()
    {
        $submission = $this->createSubmission('Sedang Diproses');
        // 3 MB file is above 2 MB limit (2048 KB)
        $file = \Illuminate\Http\UploadedFile::fake()->create('large.pdf', 3000, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Disetujui',
            'permit_file' => $file,
        ]);

        $response->assertSessionHasErrors(['permit_file']);
        $this->assertEquals('Sedang Diproses', $submission->fresh()->current_status);
    }

    public function test_pt_transition_requires_start_date_and_hakim()
    {
        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000002',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Menentukan Jadwal Wawancara'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Pembuatan Surat Keterangan Riset',
            'letter_date' => '2026-06-25',
            'start_date' => '', // Empty start_date to trigger validation failure
        ]);

        $response->assertSessionHasErrors(['start_date', 'hakim_id', 'konsentrasi', 'panitera_id']);
        $this->assertEquals('Menentukan Jadwal Wawancara', $submission->fresh()->current_status);
    }

    public function test_pt_transition_succeeds_with_valid_data()
    {
        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000003',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Menentukan Jadwal Wawancara'
        ]);

        $hakim = \App\Models\Hakim::create([
            'nama_hakim' => 'Rizky Pratama, S.H., M.H.',
            'email_hakim' => 'rizky@test.com',
        ]);

        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Budiono, S.H.',
            'nip' => '1234567890',
            'jabatan' => 'PANITERA',
            'status_aktif' => true,
        ]);

        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_pt_ind_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $placeholders = [
            'nomor_surat', 'tanggal_surat', 'program_studi', 'judul_penelitian', 'tujuan_penelitian',
            'nama_panitera', 'jabatan_panitera', 'tanggal_penelitian', 'konsentrasi', 'nama', 'npm'
        ];
        foreach ($placeholders as $p) {
            $section->addText('${' . $p . '}');
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        \App\Models\TemplateSurat::create([
            'name' => $tempFile,
            'institution_type' => 'PT',
            'template_type' => 'individu',
            'file_path' => 'templates/' . $tempFile,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Pembuatan Surat Keterangan Riset',
            'letter_date' => '2026-06-25',
            'start_date' => '2026-07-01',
            'konsentrasi' => 'Hukum Perdata',
            'hakim_id' => $hakim->id,
            'panitera_id' => $panitera->id,
        ]);

        @unlink($tempPath);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Pembuatan Surat Keterangan Riset', $submission->fresh()->current_status);
        $this->assertEquals('2026-07-01', $submission->fresh()->start_date);
        $this->assertEquals($hakim->id, $submission->fresh()->hakim_id);
    }

    public function test_pt_transition_from_generation_to_approved_with_file_upload()
    {
        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000004',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Pembuatan Surat Keterangan Riset'
        ]);

        $file = \Illuminate\Http\UploadedFile::fake()->create('permit.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Disetujui',
            'permit_file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Disetujui', $submission->fresh()->current_status);
        $this->assertNotNull($submission->fresh()->permit_file_path);
    }

    public function test_pt_transition_requires_interview_date()
    {
        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000005',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Menunggu Verifikasi'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Menentukan Jadwal Wawancara',
            'interview_date' => '', // Empty triggers error
        ]);

        $response->assertSessionHasErrors(['interview_date']);
        $this->assertEquals('Menunggu Verifikasi', $submission->fresh()->current_status);
    }

    public function test_pt_transition_to_interview_schedule_succeeds()
    {
        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000006',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Menunggu Verifikasi'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Menentukan Jadwal Wawancara',
            'interview_date' => '2026-07-25 10:00:00',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('Menentukan Jadwal Wawancara', $submission->fresh()->current_status);
        $this->assertEquals('2026-07-25 10:00:00', $submission->fresh()->interview_date->format('Y-m-d H:i:s'));
    }

    public function test_email_notification_is_sent_to_applicant_on_status_change()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $submission = Submission::create([
            'registration_number' => 'ERS-2026-000007',
            'name' => 'Jane Doe',
            'nim' => '67890',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'jane@test.com',
            'phone' => '0812345678',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum PT',
            'target_institution' => 'Pengadilan Tinggi Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'research_title' => 'Analisis Hukum PT',
            'research_location' => 'Pengadilan Tinggi Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Menunggu Verifikasi'
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.submissions.status', $submission->id), [
            'status' => 'Menentukan Jadwal Wawancara',
            'interview_date' => '2026-07-25 10:00:00',
        ]);

        $response->assertSessionHasNoErrors();
        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\SubmissionStatusUpdated::class, function ($mail) use ($submission) {
            return $mail->hasTo($submission->email) && $mail->submission->id === $submission->id;
        });
    }
}
