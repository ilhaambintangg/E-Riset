<?php

namespace Tests\Unit;

use App\Services\LetterService;
use App\Models\Submission;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LetterServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_format_recipient_position_for_rektor()
    {
        $submission = new Submission();
        $submission->recipient_position = 'Rektor';
        $submission->faculty = 'Fakultas Matematika dan Ilmu Pengetahuan Alam';
        $submission->study_program = 'Ilmu Komputer';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Rektor', $result);
    }

    public function test_format_recipient_position_for_direktur()
    {
        $submission = new Submission();
        $submission->recipient_position = 'Direktur Program Pascasarjana';
        $submission->faculty = 'Fakultas Matematika dan Ilmu Pengetahuan Alam';
        $submission->study_program = 'Ilmu Komputer';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Direktur Program Pascasarjana', $result);
    }

    public function test_format_recipient_position_for_dekan_with_fakultas_prefix()
    {
        $submission = new Submission();
        $submission->recipient_position = 'Dekan';
        $submission->faculty = 'Fakultas Matematika dan Ilmu Pengetahuan Alam';
        $submission->study_program = 'Ilmu Komputer';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Dekan Fakultas Matematika dan Ilmu Pengetahuan Alam', $result);
    }

    public function test_format_recipient_position_for_dekan_without_fakultas_prefix()
    {
        $submission = new Submission();
        $submission->recipient_position = 'dekan';
        $submission->faculty = 'Teknik';
        $submission->study_program = 'Teknik Informatika';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Dekan Fakultas Teknik', $result);
    }

    public function test_format_recipient_position_for_kaprodi()
    {
        $submission = new Submission();
        $submission->recipient_position = 'Ketua Program Studi';
        $submission->faculty = 'Fakultas Matematika dan Ilmu Pengetahuan Alam';
        $submission->study_program = 'Ilmu Komputer';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Kaprodi Ilmu Komputer', $result);
    }

    public function test_format_recipient_position_for_kaprodi_alternative()
    {
        $submission = new Submission();
        $submission->recipient_position = 'kaprodi';
        $submission->faculty = 'Fakultas Matematika dan Ilmu Pengetahuan Alam';
        $submission->study_program = 'Fisika';

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('Kaprodi Fisika', $result);
    }

    public function test_format_recipient_position_empty()
    {
        $submission = new Submission();
        $submission->recipient_position = null;

        $result = LetterService::formatRecipientPosition($submission);
        $this->assertEquals('-', $result);
    }

    public function test_to_roman_month()
    {
        $this->assertEquals('I', LetterService::toRomanMonth(1));
        $this->assertEquals('II', LetterService::toRomanMonth(2));
        $this->assertEquals('V', LetterService::toRomanMonth(5));
        $this->assertEquals('VI', LetterService::toRomanMonth(6));
        $this->assertEquals('X', LetterService::toRomanMonth(10));
        $this->assertEquals('XII', LetterService::toRomanMonth(12));
    }

    public function test_dynamic_letter_number_format()
    {
        $submission = new Submission();
        $submission->registration_number = 'ERS-2027-000015';
        
        $date = Carbon::parse('2027-10-02');
        $nomorSurat = LetterService::generateLetterNumber($submission->registration_number, $date);
        
        $this->assertEquals('   /PAN.04/SKET.HM2.1.4/X/2027', $nomorSurat);
    }

    public function test_generate_letter_for_individual()
    {
        // 1. Create a Panitera
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => '196102181987031002'
        ]);

        // 2. Create Submission
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999991',
            'name' => 'John Doe',
            'nim' => '12345',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'john@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum A',
            'target_institution' => 'PN Jakarta Pusat',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-123',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum A',
            'research_location' => 'Pengadilan Negeri Jakarta Pusat',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        // 3. Create a dummy docx template for individu
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

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'individu',
            'is_active' => true
        ]);

        // 4. Generate Letter
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $this->assertFileExists(storage_path('app/public/' . $generated->file_path));

        // Clean up
        @unlink($tempPath);
        @unlink(storage_path('app/public/' . $generated->file_path));
    }

    public function test_generate_letter_for_group()
    {
        // 1. Create a Panitera
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => '196102181987031002'
        ]);

        // 2. Create Submission
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999992',
            'name' => 'Sudarmono',
            'nim' => '22742010096',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'sudarmono@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum Kelompok',
            'target_institution' => 'PN Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-456',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum Kelompok',
            'research_location' => 'Pengadilan Negeri Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        // Add members
        $submission->members()->createMany([
            ['member_name' => 'Perdana Kusuma', 'member_npm' => '22742010001'],
            ['member_name' => 'Perdana Putra Saradi', 'member_npm' => '22742010006']
        ]);

        // 3. Create a dummy docx template for kelompok
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_kelompoka_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $generalPlaceholders = [
            'nomor_surat', 'tanggal_surat', 'jabatan_tujuan', 'universitas', 'kota_tujuan',
            'nomor_surat_pengantar', 'tanggal_surat_pengantar', 'semester', 'fakultas',
            'program_studi', 'lokasi_penelitian', 'judul_penelitian', 'tujuan_penelitian',
            'nama_panitera', 'jabatan_panitera', 'nip_panitera', 'tembusan'
        ];
        foreach ($generalPlaceholders as $p) {
            $section->addText('${' . $p . '}');
        }

        // Add a table for row cloning
        $table = $section->addTable();
        $row = $table->addRow();
        $row->addCell()->addText('${no}');
        $row->addCell()->addText('${nama_anggota}');
        $row->addCell()->addText('${npm_anggota}');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'kelompok',
            'is_active' => true
        ]);

        // 4. Generate Letter
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $this->assertFileExists(storage_path('app/public/' . $generated->file_path));

        // Clean up
        @unlink($tempPath);
        @unlink(storage_path('app/public/' . $generated->file_path));
    }

    public function test_generate_letter_for_group_fallback_to_individual()
    {
        // 1. Create a Panitera
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => '196102181987031002'
        ]);

        // 2. Create Submission with members
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999993',
            'name' => 'Sudarmono',
            'nim' => '22742010096',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'sudarmono@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum Kelompok Fallback',
            'target_institution' => 'PN Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-789',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum Kelompok Fallback',
            'research_location' => 'Pengadilan Negeri Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        $submission->members()->create([
            'member_name' => 'Perdana Kusuma', 'member_npm' => '22742010001'
        ]);

        // 3. Create only an INDIVIDUAL template in the database
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_individua_fallback_' . time() . '.docx';
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

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'individu',
            'is_active' => true
        ]);

        // 4. Generate Letter - should succeed and use individual template
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $this->assertFileExists(storage_path('app/public/' . $generated->file_path));

        // Clean up
        @unlink($tempPath);
        @unlink(storage_path('app/public/' . $generated->file_path));
    }

    public function test_generate_letter_for_group_custom_template()
    {
        // 1. Create a Panitera
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => '196102181987031002'
        ]);

        // 2. Create Submission with members
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999994',
            'name' => 'Sudarmono',
            'nim' => '22742010096',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'sudarmono@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum Kelompok Custom',
            'target_institution' => 'PN Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-999',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum Kelompok Custom',
            'research_location' => 'Pengadilan Negeri Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        $submission->members()->createMany([
            ['member_name' => 'Perdana Kusuma', 'member_npm' => '22742010001'],
            ['member_name' => 'Perdana Putra Saradi', 'member_npm' => '22742010006']
        ]);

        // 3. Create dummy docx template using custom placeholders
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_kelompoka_custom_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $generalPlaceholders = [
            'nomor_surat', 'tanggal_surat', 'jabatan_tujuan', 'universitas', 'kota_tujuan',
            'nomor_surat_pengantar', 'tanggal_surat_pengantar', 'semester', 'fakultas',
            'program_studi', 'lokasi_penelitian', 'judul_penelitian', 'tujuan_penelitian',
            'nama_panitera', 'jabatan_panitera', 'nip_panitera', 'tembusan_anggota', 'arsip'
        ];
        foreach ($generalPlaceholders as $p) {
            $section->addText('${' . $p . '}');
        }

        // Add table with 'nama' and 'npm' for cloning
        $table = $section->addTable();
        $row = $table->addRow();
        $row->addCell()->addText('${no}');
        $row->addCell()->addText('${nama}');
        $row->addCell()->addText('${npm}');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'kelompok',
            'is_active' => true
        ]);

        // 4. Generate Letter
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $this->assertFileExists(storage_path('app/public/' . $generated->file_path));

        // Clean up
        @unlink($tempPath);
        @unlink(storage_path('app/public/' . $generated->file_path));
    }

    public function test_generate_letter_for_group_filters_duplicates()
    {
        // 1. Create a Panitera
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => '196102181987031002'
        ]);

        // 2. Create Submission with members, including a duplicate of the Ketua
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999995',
            'name' => 'Sudarmono',
            'nim' => '22742010096',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'sudarmono@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum Kelompok Duplicates',
            'target_institution' => 'PN Tanjungkarang',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-888',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum Kelompok Duplicates',
            'research_location' => 'Pengadilan Negeri Tanjungkarang',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        // Add a duplicate member (same name/npm) and a legitimate member
        $submission->members()->createMany([
            ['member_name' => 'Sudarmono', 'member_npm' => '22742010096'],
            ['member_name' => 'Perdana Kusuma', 'member_npm' => '22742010001']
        ]);

        // 3. Create dummy docx template using custom placeholders
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_kelompoka_duplicates_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $generalPlaceholders = [
            'nomor_surat', 'tanggal_surat', 'jabatan_tujuan', 'universitas', 'kota_tujuan',
            'nomor_surat_pengantar', 'tanggal_surat_pengantar', 'semester', 'fakultas',
            'program_studi', 'lokasi_penelitian', 'judul_penelitian', 'tujuan_penelitian',
            'nama_panitera', 'jabatan_panitera', 'nip_panitera', 'tembusan_anggota', 'arsip'
        ];
        foreach ($generalPlaceholders as $p) {
            $section->addText('${' . $p . '}');
        }

        // Add table with 'nama' and 'npm' for cloning
        $table = $section->addTable();
        $row = $table->addRow();
        $row->addCell()->addText('${no}');
        $row->addCell()->addText('${nama}');
        $row->addCell()->addText('${npm}');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'kelompok',
            'is_active' => true
        ]);

        // 4. Generate Letter
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $outputPath = storage_path('app/public/' . $generated->file_path);
        $this->assertFileExists($outputPath);

        // 5. Verify XML contents
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($outputPath));
        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close();

        // Verify duplicate filtering in the member list table (only Sudarmono row 1, and Perdana Kusuma row 2)
        // Since we only had 1 Ketua + 1 legitimate member (the duplicate is filtered), we should have exactly 2 rows
        $this->assertEquals(2, substr_count($xmlContent, '<w:tr>'));

        // Verify that the values were correctly placed in the rows
        $this->assertStringContainsString('Sudarmono', $xmlContent);
        $this->assertStringContainsString('22742010096', $xmlContent);
        $this->assertStringContainsString('Perdana Kusuma', $xmlContent);
        $this->assertStringContainsString('22742010001', $xmlContent);

        // Verify that the duplicate Sudarmono was not added to the tembusan
        // Sdr. Sudarmono should appear in the XML:
        // 1. In the Tembusan list (once)
        // 2. In the table (once)
        // So the substr_count should be exactly 2
        $this->assertEquals(2, substr_count($xmlContent, 'Sudarmono'));
        // Sdr. Perdana Kusuma:
        // 1. In the Tembusan list (once)
        // 2. In the table (once)
        // So the substr_count should be exactly 2
        $this->assertEquals(2, substr_count($xmlContent, 'Perdana Kusuma'));

        // Verify formatting: check left alignment and tabs in the XML
        $this->assertStringContainsString('<w:jc w:val="left"/>', $xmlContent);
        $this->assertStringContainsString('<w:tab/>', $xmlContent);
        // Ensure there are no line-breaks or carriage returns stretching text inside the paragraph
        $this->assertStringContainsString('3.</w:t><w:tab/><w:t>Sdr. Sudarmono', $xmlContent);
        $this->assertStringContainsString('4.</w:t><w:tab/><w:t>Sdr. Perdana Kusuma', $xmlContent);
        $this->assertStringContainsString('5.</w:t><w:tab/><w:t>Arsip', $xmlContent);

        // Clean up
        @unlink($tempPath);
        @unlink($outputPath);
    }

    public function test_fix_placeholder_paragraph_alignment()
    {
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_align_' . time() . '.docx';
        $tempPath = $tempDir . '/' . $tempFile;

        // Create a custom docx file with specific XML containing justified paragraph for a placeholder
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        
        // Add a paragraph, set alignment to justified and right indent to 5000
        $p = $section->addText('Address: ${target_placeholder}');
        $p->getParagraphStyle()->setAlignment(\PhpOffice\PhpWord\SimpleType\Jc::BOTH);
        $p->getParagraphStyle()->setIndentation(['right' => 5000]);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($tempPath);

        // Before fix: paragraph contains <w:jc w:val="both"/> and w:right="5000"
        $ref = new \ReflectionProperty('PhpOffice\PhpWord\TemplateProcessor', 'tempDocumentMainPart');
        $ref->setAccessible(true);
        $xmlBefore = $ref->getValue($templateProcessor);
        $this->assertStringContainsString('<w:jc w:val="both"/>', $xmlBefore);
        $this->assertStringContainsString('w:right="5000"', $xmlBefore);

        // Run the alignment fix
        $refMethod = new \ReflectionMethod('App\Services\LetterService', 'fixPlaceholderParagraphAlignment');
        $refMethod->setAccessible(true);
        $refMethod->invoke(null, $templateProcessor, 'target_placeholder');

        // After fix: paragraph contains <w:jc w:val="left"/> and w:right="0"
        $xmlAfter = $ref->getValue($templateProcessor);
        $this->assertStringContainsString('<w:jc w:val="left"/>', $xmlAfter);
        $this->assertStringNotContainsString('<w:jc w:val="both"/>', $xmlAfter);
        $this->assertStringContainsString('w:right="0"', $xmlAfter);
        $this->assertStringNotContainsString('w:right="5000"', $xmlAfter);

        // Clean up
        @unlink($tempPath);
    }

    public function test_generate_letter_with_null_nip()
    {
        // 1. Create a Panitera with null NIP
        $panitera = \App\Models\Panitera::create([
            'nama_panitera' => 'Drs. H. M. Fauzan, S.H., M.H.',
            'jabatan' => 'Panitera Pengadilan Tinggi',
            'nip' => null
        ]);

        // 2. Create Submission
        $submission = \App\Models\Submission::create([
            'registration_number' => 'ERS-2026-999993',
            'name' => 'John Doe',
            'nim' => '12345',
            'university' => 'Universitas Indonesia',
            'faculty' => 'Fakultas Hukum',
            'study_program' => 'Ilmu Hukum',
            'email' => 'john@test.com',
            'phone' => '08123456',
            'address' => 'Jakarta',
            'title' => 'Analisis Hukum A',
            'target_institution' => 'PN Jakarta Pusat',
            'purpose' => 'Skripsi',
            'methodology' => 'Kualitatif',
            'start_date' => '2026-06-25',
            'end_date' => '2026-07-25',
            'recipient_position' => 'Dekan',
            'destination_city' => 'Jakarta',
            'reference_letter_number' => 'REF-123',
            'reference_letter_date' => '2026-06-20',
            'research_title' => 'Analisis Hukum A',
            'research_location' => 'Pengadilan Negeri Jakarta Pusat',
            'research_type' => 'Skripsi',
            'current_status' => 'Disetujui'
        ]);

        // 3. Create dummy template file
        $tempDir = storage_path('app/public/templates');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $tempFile = 'test_temp_null_nip_' . time() . '.docx';
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

        $template = \App\Models\TemplateSurat::create([
            'file_path' => 'templates/' . $tempFile,
            'type' => 'individu',
            'is_active' => true
        ]);

        // 4. Generate Letter
        $letterService = new LetterService();
        $generated = $letterService->generateLetter($submission, $panitera->id, '2026-06-25');

        $this->assertNotNull($generated);
        $outputPath = storage_path('app/public/' . $generated->file_path);
        $this->assertFileExists($outputPath);

        // 5. Verify NIP content
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($outputPath));
        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close();

        // Verify that nip_panitera was replaced (placeholder is gone)
        $this->assertStringNotContainsString('${nip_panitera}', $xmlContent);

        // Clean up
        @unlink($tempPath);
        @unlink($outputPath);
    }
}
