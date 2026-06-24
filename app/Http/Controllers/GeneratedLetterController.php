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
    public static function generateLetter(Submission $submission, $paniteraId)
    {
        $panitera = Panitera::findOrFail($paniteraId);
        $template = TemplateSurat::where('is_active', true)->first();

        if (!$template) {
            throw new \Exception('Template surat belum tersedia atau tidak ada yang aktif');
        }

        $templatePath = storage_path('app/public/' . $template->file_path);
        if (!file_exists($templatePath)) {
            throw new \Exception('File template tidak ditemukan');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace Placeholders
        $templateProcessor->setValue('nomor_surat', 'W9.U/' . $submission->registration_number . '/HK.02/' . date('m/Y'));
        $templateProcessor->setValue('tanggal_surat', date('d F Y'));
        $templateProcessor->setValue('nama', $submission->name);
        $templateProcessor->setValue('npm', $submission->nim ?? '-');
        $templateProcessor->setValue('semester', $submission->semester ?? '-');
        $templateProcessor->setValue('universitas', $submission->university);
        $templateProcessor->setValue('fakultas', $submission->faculty);
        $templateProcessor->setValue('program_studi', $submission->study_program);
        $templateProcessor->setValue('judul_penelitian', $submission->title);
        $templateProcessor->setValue('lokasi_penelitian', $submission->location ?? '-');
        
        // Panitera
        $templateProcessor->setValue('nama_panitera', $panitera->nama_panitera);
        $templateProcessor->setValue('jabatan_panitera', $panitera->jabatan ?? 'Panitera');
        $templateProcessor->setValue('nip_panitera', $panitera->nip);

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
        ]);

        $submission = Submission::findOrFail($submissionId);
        if ($submission->current_status !== 'Disetujui') {
            return response()->json(['message' => 'Permohonan belum disetujui'], 400);
        }

        try {
            $generatedLetter = self::generateLetter($submission, $request->panitera_id);
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
