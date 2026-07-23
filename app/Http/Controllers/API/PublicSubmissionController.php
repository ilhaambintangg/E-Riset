<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\Faq;
use App\Models\Announcement;
use App\Repositories\SubmissionRepository;
use App\Services\SubmissionService;
use App\Http\Requests\API\Submission\StoreSubmissionRequest;
use Illuminate\Support\Facades\Storage;

class PublicSubmissionController extends Controller
{
    protected $submissionRepository;
    protected $submissionService;

    public function __construct(
        SubmissionRepository $submissionRepository,
        SubmissionService $submissionService
    ) {
        $this->submissionRepository = $submissionRepository;
        $this->submissionService = $submissionService;
    }

    /**
     * Get list of requirements.
     */
    public function getRequirements()
    {
        $requirements = Requirement::orderBy('is_required', 'desc')->get();
        return response()->json($requirements);
    }

    /**
     * Get list of FAQs.
     */
    public function getFaqs()
    {
        $faqs = Faq::all();
        return response()->json($faqs);
    }

    /**
     * Get active announcements.
     */
    public function getAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return response()->json($announcements);
    }

    /**
     * Submit a new research permit application.
     */
    public function store(StoreSubmissionRequest $request)
    {
        $validatedData = $request->validated();
        $files = [
            'proposal_penelitian' => $request->file('proposal_penelitian'),
            'surat_pengantar_kampus' => $request->file('surat_pengantar_kampus'),
        ];

        try {
            $submission = $this->submissionService->createSubmission($validatedData, $files);
            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil dikirim',
                'registration_number' => $submission->registration_number
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses permohonan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track status of an application.
     */
    public function track($registration_number)
    {
        $submission = $this->submissionRepository->findByRegistrationNumberWithSortedLogs($registration_number);

        if (!$submission) {
            return response()->json([
                'message' => 'Nomor registrasi tidak ditemukan.'
            ], 404);
        }

        return response()->json($submission);
    }

    /**
     * Download the final approved permit PDF.
     */
    public function downloadPermit($registration_number)
    {
        $submission = $this->submissionRepository->findByRegistrationNumber($registration_number);

        if (!$submission) {
            return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
        }

        if ($submission->isPt()) {
            if ($submission->current_status !== 'Pembuatan Surat Keterangan Riset') {
                return response()->json(['message' => 'Surat keterangan riset belum tersedia.'], 400);
            }
            
            // If the Hukum officer uploaded a signed PDF
            if ($submission->permit_file_path && Storage::disk('public')->exists($submission->permit_file_path)) {
                return Storage::disk('public')->download($submission->permit_file_path);
            }

            // Otherwise download the generated Word (.docx) letter
            if ($submission->generatedLetter && Storage::disk('public')->exists($submission->generatedLetter->file_path)) {
                return Storage::disk('public')->download($submission->generatedLetter->file_path);
            }

            return response()->json(['message' => 'File surat keterangan belum dihasilkan.'], 404);
        } else {
            // PN flow
            if ($submission->current_status !== 'Disetujui' || !$submission->permit_file_path) {
                return response()->json(['message' => 'Surat izin belum tersedia atau permohonan belum disetujui.'], 400);
            }

            if (!Storage::disk('public')->exists($submission->permit_file_path)) {
                return response()->json(['message' => 'File surat izin tidak ditemukan di server.'], 404);
            }

            return Storage::disk('public')->download($submission->permit_file_path);
        }
    }
}
