<?php

namespace App\Services;

use App\Models\GeneratedLetter;
use App\Models\Panitera;
use App\Models\Submission;
use App\Models\TemplateSurat;
use App\Models\WebSetting;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class LetterService
{
    /**
     * Convert month number to Roman numeral.
     *
     * @param int $monthNum
     * @return string
     */
    public static function toRomanMonth(int $monthNum): string
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romans[$monthNum] ?? '';
    }

    /**
     * Generate dynamic letter number.
     *
     * @param string $registrationNumber
     * @param Carbon $date
     * @return string
     */
    public static function generateLetterNumber(string $registrationNumber, Carbon $date): string
    {
        $setting = WebSetting::first();
        $letterCode = $setting->letter_code ?? 'PAN.04/SKET.HM2.1.4';
        
        $bulanRomawi = self::toRomanMonth($date->month);
        $tahun = $date->year;

        return '   /' . $letterCode . '/' . $bulanRomawi . '/' . $tahun;
    }

    /**
     * Format recipient position dynamically based on type.
     *
     * @param Submission $submission
     * @return string
     */
    public static function formatRecipientPosition(Submission $submission): string
    {
        $recipientPosition = trim($submission->recipient_position ?? '');
        if ($recipientPosition === '') {
            return '-';
        }

        if (strcasecmp($recipientPosition, 'Dekan') === 0) {
            $faculty = trim($submission->faculty ?? '');
            if (!empty($faculty)) {
                if (!str_starts_with(strtolower($faculty), 'fakultas')) {
                    $faculty = 'Fakultas ' . $faculty;
                }
                return 'Dekan ' . $faculty;
            }
            return 'Dekan';
        }

        if (strcasecmp($recipientPosition, 'Ketua Program Studi') === 0 || strcasecmp($recipientPosition, 'Kaprodi') === 0) {
            $studyProgram = trim($submission->study_program ?? '');
            if (!empty($studyProgram)) {
                return 'Kaprodi ' . $studyProgram;
            }
            return 'Kaprodi';
        }

        return $recipientPosition;
    }

    /**
     * Validate template placeholders.
     *
     * @param TemplateProcessor $templateProcessor
     * @throws \Exception
     */
    public static function validateTemplate(TemplateProcessor $templateProcessor, bool $isKelompok = false): void
    {
        $variables = $templateProcessor->getVariables();
        
        $requiredPlaceholders = [
            'nomor_surat',
            'tanggal_surat',
            'jabatan_tujuan',
            'universitas',
            'kota_tujuan',
            'fakultas',
            'program_studi',
            'judul_penelitian',
            'lokasi_penelitian',
            'nama_panitera',
            'jabatan_panitera',
            'nip_panitera'
        ];

        if ($isKelompok) {
            if (in_array('nama_anggota', $variables)) {
                $requiredPlaceholders[] = 'nama_anggota';
                $requiredPlaceholders[] = 'npm_anggota';
            } else {
                $requiredPlaceholders[] = 'nama';
                $requiredPlaceholders[] = 'npm';
            }

            if (in_array('tembusan', $variables)) {
                $requiredPlaceholders[] = 'tembusan';
            } else {
                $requiredPlaceholders[] = 'tembusan_anggota';
                $requiredPlaceholders[] = 'arsip';
            }
        } else {
            $requiredPlaceholders[] = 'nama';
            $requiredPlaceholders[] = 'npm';
        }

        $missing = [];
        foreach ($requiredPlaceholders as $placeholder) {
            if (!in_array($placeholder, $variables)) {
                $missing[] = "\${{$placeholder}}";
            }
        }

        if (!empty($missing)) {
            throw new \Exception("Template tidak valid. Placeholder berikut tidak ditemukan: " . implode(', ', $missing));
        }
    }

    /**
     * Generate the letter document.
     *
     * @param Submission $submission
     * @param int $paniteraId
     * @param string|null $letterDate
     * @return GeneratedLetter
     * @throws \Exception
     */
    public function generateLetter(Submission $submission, int $paniteraId, ?string $letterDate = null): GeneratedLetter
    {
        $panitera = Panitera::findOrFail($paniteraId);
        
        // Determine template type (individu or kelompok)
        $isKelompok = $submission->members()->count() > 0;
        $templateType = $isKelompok ? 'kelompok' : 'individu';

        $template = TemplateSurat::where('type', $templateType)->where('is_active', true)->first();
        if (!$template) {
            $template = TemplateSurat::where('is_active', true)->first();
        }

        if (!$template) {
            throw new \Exception("Template surat untuk jenis {$templateType} belum tersedia atau tidak ada yang aktif.");
        }

        $templatePath = storage_path('app/public/' . $template->file_path);
        if (!file_exists($templatePath)) {
            throw new \Exception('File template tidak ditemukan di server.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Fix potential justified paragraph layout stretching and narrow margins for address block placeholders
        self::fixPlaceholderParagraphAlignment($templateProcessor, 'universitas');
        self::fixPlaceholderParagraphAlignment($templateProcessor, 'jabatan_tujuan');
        self::fixPlaceholderParagraphAlignment($templateProcessor, 'kota_tujuan');

        $isKelompokTemplate = ($template->type === 'kelompok');

        // Validate template placeholders
        self::validateTemplate($templateProcessor, $isKelompokTemplate);

        // Parse dates using Carbon
        $refDate = $submission->reference_letter_date ? Carbon::parse($submission->reference_letter_date) : null;
        $tanggalSuratPengantar = $refDate ? $refDate->locale('id')->translatedFormat('d F Y') : '-';

        $date = $letterDate ? Carbon::parse($letterDate) : Carbon::now();
        $tanggalSurat = $date->locale('id')->translatedFormat('d F Y');

        // Generate dynamic letter number
        $nomorSurat = self::generateLetterNumber($submission->registration_number, $date);

        // Replace placeholders in Word document
        $templateProcessor->setValue('nomor_surat', $nomorSurat);
        $templateProcessor->setValue('tanggal_surat', $tanggalSurat);
        $templateProcessor->setValue('jabatan_tujuan', self::formatRecipientPosition($submission));
        $templateProcessor->setValue('universitas', $submission->university ?? '-');
        $templateProcessor->setValue('kota_tujuan', $submission->destination_city ?? '-');
        $templateProcessor->setValue('nomor_surat_pengantar', $submission->reference_letter_number ?? '-');
        $templateProcessor->setValue('tanggal_surat_pengantar', $tanggalSuratPengantar);
        
        if (!$isKelompokTemplate) {
            $templateProcessor->setValue('nama', $submission->name);
            $templateProcessor->setValue('npm', $submission->nim ?? '-');
        }

        $templateProcessor->setValue('semester', $submission->semester ?? '-');
        $templateProcessor->setValue('fakultas', $submission->faculty ?? '-');
        $templateProcessor->setValue('program_studi', $submission->study_program ?? '-');
        $templateProcessor->setValue('lokasi_penelitian', $submission->research_location ?? '-');
        $templateProcessor->setValue('judul_penelitian', $submission->research_title ?? '-');
        $templateProcessor->setValue('tujuan_penelitian', $submission->research_type ?? '-');

        // Replace Panitera Details
        $templateProcessor->setValue('nama_panitera', $panitera->nama_panitera);
        $templateProcessor->setValue('jabatan_panitera', $panitera->jabatan ?? 'Panitera');
        $templateProcessor->setValue('nip_panitera', $panitera->nip);

        // Handle Group Submission (Kelompok) members list & tembusan list
        if ($isKelompokTemplate) {
            // Filter out duplicate members whose name or npm matches the Ketua Kelompok
            $members = $submission->members->reject(function ($member) use ($submission) {
                return strcasecmp(trim($member->member_name), trim($submission->name)) === 0
                    || (!empty($submission->nim) && !empty($member->member_npm) && strcasecmp(trim($member->member_npm), trim($submission->nim)) === 0);
            })->values();

            $totalCount = 1 + count($members);

            // Determine table row placeholders
            $variables = $templateProcessor->getVariables();
            $cloneKey = in_array('nama_anggota', $variables) ? 'nama_anggota' : 'nama';
            $npmKey = in_array('npm_anggota', $variables) ? 'npm_anggota' : 'npm';

            $templateProcessor->cloneRow($cloneKey, $totalCount);

            // Row 1: Ketua Kelompok (from main submission)
            $templateProcessor->setValue('no#1', 1);
            $templateProcessor->setValue($cloneKey . '#1', $submission->name);
            $templateProcessor->setValue($npmKey . '#1', $submission->nim ?? '-');

            // Subsequent rows: Members
            foreach ($members as $index => $member) {
                $rowNumber = $index + 2;
                $templateProcessor->setValue('no#' . $rowNumber, $rowNumber);
                $templateProcessor->setValue($cloneKey . '#' . $rowNumber, $member->member_name);
                $templateProcessor->setValue($npmKey . '#' . $rowNumber, $member->member_npm);
            }

            // Generate automatic tembusan list
            if (in_array('tembusan', $variables)) {
                $tembusanItems = [];
                $tembusanItems[] = "Ketua Pengadilan Tinggi Tanjungkarang\n   (Sebagai laporan)";

                $targetPn = "Ketua Pengadilan Negeri Tanjungkarang";
                $location = trim($submission->research_location ?? '');
                if (!empty($location)) {
                    if (stripos($location, 'Pengadilan Negeri') !== false) {
                        $targetPn = "Ketua " . $location;
                    } elseif (stripos($location, 'PN ') === 0 || stripos($location, 'PN. ') === 0) {
                        $targetPn = "Ketua Pengadilan Negeri " . preg_replace('/^PN\.?\s+/i', '', $location);
                    }
                }
                $tembusanItems[] = $targetPn;
                $tembusanItems[] = "Sdr. " . $submission->name;
                foreach ($members as $member) {
                    $tembusanItems[] = "Sdr. " . $member->member_name;
                }
                $tembusanItems[] = "Arsip";

                $templateProcessor->replaceXmlBlock('tembusan', self::formatTembusanXml($tembusanItems, 1), 'w:p');
            } else {
                // User's custom layout: replace 'tembusan_anggota' and 'arsip'
                $tembusanItems = [];
                $tembusanItems[] = "Sdr. " . $submission->name;
                foreach ($members as $member) {
                    $tembusanItems[] = "Sdr. " . $member->member_name;
                }

                $templateProcessor->replaceXmlBlock('tembusan_anggota', self::formatTembusanXml($tembusanItems, 3), 'w:p');
                
                $arsipNum = 3 + count($tembusanItems);
                $templateProcessor->replaceXmlBlock('arsip', self::formatTembusanXml(['Arsip'], $arsipNum), 'w:p');
            }
        }

        // Save Generated Letter
        $outputDir = 'generated_letters/' . $submission->registration_number;
        if (!Storage::disk('public')->exists($outputDir)) {
            Storage::disk('public')->makeDirectory($outputDir);
        }

        $fileName = "Surat_Izin_{$submission->registration_number}.docx";
        $outputPath = storage_path("app/public/{$outputDir}/{$fileName}");
        $templateProcessor->saveAs($outputPath);

        $relativePath = "{$outputDir}/{$fileName}";

        // Update or create GeneratedLetter record
        $generatedLetter = GeneratedLetter::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'panitera_id' => $panitera->id,
                'file_path' => $relativePath
            ]
        );

        return $generatedLetter;
    }

    /**
     * Format list of items into Word XML paragraphs with left-alignment and correct indents.
     *
     * @param array $items
     * @param int $startNum
     * @return string
     */
    private static function formatTembusanXml(array $items, int $startNum = 1): string
    {
        $xml = '';
        
        $pStyleNumbered = '<w:pPr><w:ind w:left="284" w:right="291" w:hanging="284"/><w:jc w:val="left"/><w:rPr><w:rFonts w:ascii="Bookman Old Style" w:hAnsi="Bookman Old Style"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:pPr>';
        $pStyleSub = '<w:pPr><w:ind w:left="284" w:right="291"/><w:jc w:val="left"/><w:rPr><w:rFonts w:ascii="Bookman Old Style" w:hAnsi="Bookman Old Style"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:pPr>';
        $rStyle = '<w:rPr><w:rFonts w:ascii="Bookman Old Style" w:hAnsi="Bookman Old Style"/><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr>';

        $num = $startNum;
        foreach ($items as $item) {
            $lines = explode("\n", $item);
            foreach ($lines as $lineIndex => $line) {
                $trimmedLine = trim($line);
                if ($trimmedLine === '') {
                    continue;
                }
                
                if ($lineIndex === 0) {
                    // Numbered paragraph
                    $xml .= '<w:p>' . $pStyleNumbered . '<w:r>' . $rStyle . '<w:t>' . $num . '.</w:t><w:tab/><w:t>' . htmlspecialchars($trimmedLine, ENT_XML1, 'UTF-8') . '</w:t></w:r></w:p>';
                } else {
                    // Sub-line paragraph
                    $xml .= '<w:p>' . $pStyleSub . '<w:r>' . $rStyle . '<w:t>' . htmlspecialchars($trimmedLine, ENT_XML1, 'UTF-8') . '</w:t></w:r></w:p>';
                }
            }
            $num++;
        }

        return $xml;
    }

    /**
     * Fix paragraph alignment for specific placeholders by changing both/justified to left.
     *
     * @param TemplateProcessor $templateProcessor
     * @param string $placeholder The placeholder name (e.g. 'universitas')
     */
    private static function fixPlaceholderParagraphAlignment(TemplateProcessor $templateProcessor, string $placeholder): void
    {
        try {
            $ref = new \ReflectionProperty('PhpOffice\PhpWord\TemplateProcessor', 'tempDocumentMainPart');
            $ref->setAccessible(true);
            $xml = $ref->getValue($templateProcessor);

            // Pattern to match the paragraph containing the placeholder
            $escapedPlaceholder = preg_quote($placeholder, '/');
            $pattern = '/(<w:p\b[^>]*>(?:(?!<\/w:p>).)*' . $escapedPlaceholder . '(?:(?!<\/w:p>).)*<\/w:p>)/s';

            $xml = preg_replace_callback($pattern, function ($matches) {
                $pContent = $matches[1];
                
                // Replace both/justified justification with left justification
                if (str_contains($pContent, '<w:jc w:val="both"/>')) {
                    $pContent = str_replace('<w:jc w:val="both"/>', '<w:jc w:val="left"/>', $pContent);
                }

                // Reset narrow right indent to allow text to extend horizontally
                $pContent = preg_replace('/\bw:right="\d+"/', 'w:right="0"', $pContent);
                
                return $pContent;
            }, $xml);

            $ref->setValue($templateProcessor, $xml);
        } catch (\Exception $e) {
            // Ignore error so it doesn't block letter generation
        }
    }
}
