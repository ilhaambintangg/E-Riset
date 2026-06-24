<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\SubmissionStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    /**
     * Get statistics for admin dashboard.
     */
    public function getStats()
    {
        $stats = [
            'total' => Submission::count(),
            'pending' => Submission::where('current_status', 'Menunggu Verifikasi')->count(),
            'processing' => Submission::where('current_status', 'Sedang Diproses')->count(),
            'approved' => Submission::where('current_status', 'Disetujui')->count(),
            'rejected' => Submission::where('current_status', 'Ditolak')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get list of submissions with filter & search.
     */
    public function getSubmissions(Request $request)
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
        return response()->json($submissions);
    }

    /**
     * Get detail of a submission.
     */
    public function getSubmissionDetail($id)
    {
        $submission = Submission::with(['documents', 'statusLogs' => function($q) {
            $q->with('admin')->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$submission) {
            return response()->json(['message' => 'Permohonan tidak ditemukan.'], 404);
        }

        return response()->json($submission);
    }

    /**
     * Update status of a submission.
     */
    public function updateStatus(Request $request, $id)
    {
        $submission = Submission::find($id);

        if (!$submission) {
            return response()->json(['message' => 'Permohonan tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Menunggu Verifikasi,Sedang Diproses,Disetujui,Ditolak'],
            'notes' => ['nullable', 'string'],
            'panitera_id' => ['nullable', 'exists:panitera,id'], // Required if status is Sedang Diproses
            'permit_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // Required if status is Disetujui
        ]);

        if ($validated['status'] === 'Sedang Diproses' && empty($validated['panitera_id'])) {
            return response()->json(['message' => 'Panitera harus dipilih saat memproses permohonan untuk generate surat izin.'], 422);
        }

        if ($validated['status'] === 'Disetujui' && !$request->hasFile('permit_file') && empty($submission->permit_file_path)) {
            return response()->json(['message' => 'Surat izin (PDF yang sudah ditandatangani) harus diunggah saat menyetujui permohonan.'], 422);
        }

        return DB::transaction(function () use ($request, $submission, $validated) {
            $admin = Auth::user();
            
            // Generate letter automatically if status is Sedang Diproses
            if ($validated['status'] === 'Sedang Diproses') {
                try {
                    \App\Http\Controllers\GeneratedLetterController::generateLetter($submission, $validated['panitera_id']);
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
                // Log email error
                \Illuminate\Support\Facades\Log::error('Failed to send SubmissionStatusUpdated email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Status permohonan berhasil diperbarui.',
                'submission' => $submission
            ]);
        });
    }
}
