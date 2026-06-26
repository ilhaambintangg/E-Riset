<?php

namespace App\Http\Controllers;

use App\Models\GeneratedLetter;
use App\Models\Submission;
use App\Services\LetterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneratedLetterController extends Controller
{
    protected $letterService;

    /**
     * GeneratedLetterController constructor.
     *
     * @param LetterService $letterService
     */
    public function __construct(LetterService $letterService)
    {
        $this->letterService = $letterService;
    }

    /**
     * Generate automatic letter for submission.
     *
     * @param Request $request
     * @param int $submissionId
     * @return \Illuminate\Http\JsonResponse
     */
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
            $generatedLetter = $this->letterService->generateLetter($submission, $request->panitera_id, $request->letter_date);
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

    /**
     * Download the generated letter file.
     *
     * @param int $submissionId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function download($submissionId)
    {
        $generatedLetter = GeneratedLetter::where('submission_id', $submissionId)->first();
        if (!$generatedLetter || !Storage::disk('public')->exists($generatedLetter->file_path)) {
            return response()->json(['message' => 'Surat belum di-generate atau file tidak ditemukan'], 404);
        }

        return response()->download(storage_path("app/public/" . $generatedLetter->file_path));
    }
}
