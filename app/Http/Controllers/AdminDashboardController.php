<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\SubmissionStatus;
use App\Models\Panitera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    protected $letterService;

    public function __construct(\App\Services\LetterService $letterService)
    {
        $this->letterService = $letterService;
    }
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total' => Submission::count(),
            'pending' => Submission::where('current_status', 'Menunggu Verifikasi')->count(),
            'processing' => Submission::where('current_status', 'Sedang Diproses')->count(),
            'approved' => Submission::where('current_status', 'Disetujui')->count(),
            'rejected' => Submission::where('current_status', 'Ditolak')->count(),
        ];

        $recentSubmissions = Submission::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions'));
    }

    /**
     * Show list of submissions with filter & search.
     */
    public function submissions(Request $request)
    {
        $query = Submission::query();

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        // Search Query
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('university', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * Show detail of a submission.
     */
    public function submissionDetail($id)
    {
        $submission = Submission::with(['documents', 'statusLogs' => function($q) {
            $q->with('admin')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

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
    public function updateStatus(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Menunggu Verifikasi,Sedang Diproses,Disetujui,Ditolak'],
            'notes' => ['nullable', 'string'],
            'panitera_id' => ['nullable', 'exists:panitera,id'], // Required if status is Sedang Diproses
            'letter_date' => ['nullable', 'date'], // Required if status is Sedang Diproses
            'permit_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // Required if status is Disetujui
        ]);

        if ($validated['status'] === 'Sedang Diproses') {
            if (empty($validated['panitera_id'])) {
                return back()->withErrors(['panitera_id' => 'Panitera harus dipilih saat memproses permohonan untuk generate surat izin.'])->withInput();
            }
            if (empty($validated['letter_date'])) {
                return back()->withErrors(['letter_date' => 'Tanggal surat harus diisi saat memproses permohonan untuk generate surat izin.'])->withInput();
            }
        }

        if ($validated['status'] === 'Disetujui' && !$request->hasFile('permit_file') && empty($submission->permit_file_path)) {
            return back()->withErrors(['permit_file' => 'Surat izin (PDF yang sudah ditandatangani) harus diunggah saat menyetujui permohonan.'])->withInput();
        }

        try {
            DB::transaction(function () use ($request, $submission, $validated) {
                $admin = Auth::user();
                
                // Generate letter automatically if status is Sedang Diproses
                if ($validated['status'] === 'Sedang Diproses') {
                    try {
                        $this->letterService->generateLetter($submission, $validated['panitera_id'], $validated['letter_date']);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Auto-generate letter failed: ' . $e->getMessage());
                        throw new \Exception('Gagal membuat surat: ' . $e->getMessage());
                    }
                }

                // Save the uploaded permit file if status is Disetujui
                if ($validated['status'] === 'Disetujui' && $request->hasFile('permit_file')) {
                    $file = $request->file('permit_file');
                    $fileName = "Izin_Penelitian_{$submission->registration_number}." . $file->getClientOriginalExtension();
                    $path = $file->storeAs('permits/' . $submission->registration_number, $fileName, 'public');
                    $submission->permit_file_path = $path;
                }

                // Update main submission record
                $submission->current_status = $validated['status'];
                $submission->admin_notes = $validated['notes'] ?? null;
                $submission->save();

                // Create log entry
                SubmissionStatus::create([
                    'submission_id' => $submission->id,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? "Status diubah menjadi {$validated['status']}.",
                    'changed_by_admin_id' => $admin ? $admin->id : null,
                ]);

                // Send Email Notification
                try {
                    \Illuminate\Support\Facades\Mail::to($submission->email)->send(new \App\Mail\SubmissionStatusUpdated($submission));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send SubmissionStatusUpdated email: ' . $e->getMessage());
                }
            });

            return back()->with('success', 'Status permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['_global' => $e->getMessage()])->withInput();
        }
    }
}
