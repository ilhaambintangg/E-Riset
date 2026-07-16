<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panitera;
use App\Repositories\SubmissionRepository;
use App\Services\SubmissionService;
use App\Http\Requests\Admin\Submission\UpdateSubmissionStatusRequest;

class AdminDashboardController extends Controller
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

    public function dashboard()
    {
        $stats = [
            'total' => $this->submissionRepository->countAll(),
            'pending' => $this->submissionRepository->countByStatus('Menunggu Verifikasi'),
            'processing' => $this->submissionRepository->countByStatus('Sedang Diproses'),
            'approved' => $this->submissionRepository->countByStatus('Disetujui'),
            'rejected' => $this->submissionRepository->countByStatus('Ditolak'),
        ];

        $recentSubmissions = $this->submissionRepository->recent(5);

        return view('admin.dashboard', compact('stats', 'recentSubmissions'));
    }

    /**
     * Show list of submissions with filter & search.
     */
    public function submissions(Request $request)
    {
        $filters = [
            'status' => $request->query('status'),
            'search' => $request->query('search'),
        ];

        $submissions = $this->submissionRepository->getSubmissionsPaginated($filters, 5);
        
        $stats = [
            'total' => $this->submissionRepository->countAll(),
            'pending' => $this->submissionRepository->countByStatus('Menunggu Verifikasi'),
            'processing' => $this->submissionRepository->countByStatus('Sedang Diproses'),
            'approved' => $this->submissionRepository->countByStatus('Disetujui'),
            'rejected' => $this->submissionRepository->countByStatus('Ditolak'),
        ];
        
        return view('admin.submissions.index', compact('submissions', 'stats'));
    }

    /**
     * Show detail of a submission.
     */
    public function submissionDetail($id)
    {
        $submission = $this->submissionRepository->findOrFail($id, [
            'documents',
            'statusLogs.admin'
        ]);

        if (!$submission->is_read) {
            $submission->is_read = true;
            $submission->save();
        }

        $paniteras = Panitera::where('status_aktif', true)->get();

        return view('admin.submissions.show', compact('submission', 'paniteras'));
    }

    /**
     * Update status of a submission.
     */
    public function updateStatus(UpdateSubmissionStatusRequest $request, $id)
    {
        $submission = $this->submissionRepository->findOrFail($id);
        $validated = $request->validated();
        $permitFile = $request->file('permit_file');

        try {
            $this->submissionService->updateStatus($submission, $validated, $permitFile);
            return back()->with('success', 'Status permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['_global' => $e->getMessage()])->withInput();
        }
    }
}
