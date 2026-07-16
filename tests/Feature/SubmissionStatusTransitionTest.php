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
            'file_path' => 'templates/' . $tempFile,
            'type' => 'individu',
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
}
