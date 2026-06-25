<?php

namespace App\Http\Controllers;

use App\Models\GeneratedLetter;
use App\Models\Panitera;
use App\Models\Submission;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class GeneratedLetterController extends Controller
{
    public static function generateLetter(Submission $submission, $paniteraId, $letterDate = null)
    {
        $panitera = Panitera::findOrFail($paniteraId);
        
        // Check if Kelompok (has members in submission_members)
        $isKelompok = $submission->members()->count() > 0;
        $templateType = $isKelompok ? 'kelompok' : 'individu';

        $template = TemplateSurat::where('type', $templateType)->where('is_active', true)->first();

        if (!$template) {
            // Backwards compatibility fallback to any active template
            $template = TemplateSurat::where('is_active', true)->first();
        }

        if (!$template) {
            throw new \Exception("Template surat untuk jenis {$templateType} belum tersedia atau tidak ada yang aktif");
        }

        $templatePath = storage_path('app/public/' . $template->file_path);
        if (!file_exists($templatePath)) {
            throw new \Exception('File template tidak ditemukan');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Month mapping for Indonesian dates
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
            '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        // Format reference letter date
        $tanggalSuratPengantar = '-';
        if ($submission->reference_letter_date) {
            $dateParts = explode('-', $submission->reference_letter_date);
            if (count($dateParts) === 3) {
                $tanggalSuratPengantar = $dateParts[2] . ' ' . ($months[$dateParts[1]] ?? $dateParts[1]) . ' ' . $dateParts[0];
            }
        }

        // Format court letter date
        $targetDate = $letterDate ?: date('Y-m-d');
        $dateParts = explode('-', $targetDate);
        if (count($dateParts) === 3) {
            $tanggalSurat = $dateParts[2] . ' ' . ($months[$dateParts[1]] ?? $dateParts[1]) . ' ' . $dateParts[0];
        } else {
            $tanggalSurat = date('d') . ' ' . ($months[date('n')] ?? date('F')) . ' ' . date('Y');
        }

        // Replace Base Placeholders
        $templateProcessor->setValue('nomor_surat', 'W9.U/' . $submission->registration_number . '/HK.02/' . date('m/Y'));
        $templateProcessor->setValue('tanggal_surat', $tanggalSurat);
        
        $templateProcessor->setValue('jabatan_tujuan', $submission->recipient_position ?? '-');
        $templateProcessor->setValue('universitas', $submission->university ?? '-');
        $templateProcessor->setValue('kota_tujuan', $submission->destination_city ?? '-');
        $templateProcessor->setValue('nomor_surat_pengantar', $submission->reference_letter_number ?? '-');
        $templateProcessor->setValue('tanggal_surat_pengantar', $tanggalSuratPengantar);
        
        $templateProcessor->setValue('nama', $submission->name);
        $templateProcessor->setValue('npm', $submission->nim ?? '-');
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

        // Handle Group Submission (Kelompok) members list
        if ($isKelompok) {
            $members = $submission->members;
            $templateProcessor->cloneRow('nama_anggota', count($members));
            foreach ($members as $index => $member) {
                $rowNumber = $index + 1;
                $templateProcessor->setValue('no#' . $rowNumber, $rowNumber);
                $templateProcessor->setValue('nama_anggota#' . $rowNumber, $member->member_name);
                $templateProcessor->setValue('npm_anggota#' . $rowNumber, $member->member_npm);
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

        // Check if already generated, update or create
        $generatedLetter = GeneratedLetter::updateOrCreate(
            ['submission_id' => $submission->id],
            [
                'panitera_id' => $panitera->id,
                'file_path' => $relativePath
            ]
        );

        return $generatedLetter;
    }

    public function generate(Request $request, $submissionId)
    {
        $request->validate([
            'panitera_id' => 'required|exists:panitera,id',
            'letter_date' => 'nullable|date',
        ]);

        $submission = Submission::findOrFail($submissionId);
        if ($submission->current_status !== 'Disetujui') {
            return response()->json(['message' => 'Permohonan belum disetujui'], 400);
        }

        try {
            $generatedLetter = self::generateLetter($submission, $request->panitera_id, $request->letter_date);
            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil di-generate',
                'download_url' => url("storage/" . $generatedLetter->file_path)
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error generating letter: " . $e->getMessage());
            return response()->json(['message' => 'Gagal membuat surat: ' . $e->getMessage()], 500);
        }
    }

    public function download($submissionId)
    {
        $generatedLetter = GeneratedLetter::where('submission_id', $submissionId)->first();
        if (!$generatedLetter || !Storage::disk('public')->exists($generatedLetter->file_path)) {
            return response()->json(['message' => 'Surat belum di-generate atau file tidak ditemukan'], 404);
        }

        return response()->download(storage_path("app/public/" . $generatedLetter->file_path));
    }
}
