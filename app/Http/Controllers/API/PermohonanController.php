<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SubmissionService;
use App\Http\Requests\API\Submission\StoreSubmissionRequest;
use App\Http\Requests\API\Permohonan\StorePermohonanRequest;

class PermohonanController extends Controller
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    /**
     * Simpan permohonan penelitian baru (Simplified Form).
     */
    public function store(StorePermohonanRequest $request)
    {
        $validated = $request->validated();
        $files = [
            'surat_pengantar' => $request->file('surat_pengantar'),
            'proposal_penelitian' => $request->file('proposal_penelitian'),
        ];

        try {
            $submission = $this->submissionService->createSimplifiedPermohonan($validated, $files);
            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin penelitian berhasil dikirim. Silakan menunggu proses verifikasi dari Pengadilan Tinggi Tanjungkarang.',
                'registration_number' => $submission->registration_number,
                'data'    => $submission,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses permohonan: ' . $e->getMessage()
            ], 500);
        }
    }
}
