<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Document;
use App\Models\SubmissionStatus;
use App\Models\Requirement;
use App\Models\Faq;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicSubmissionController extends Controller
{
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
    public function store(Request $request)
    {
        // 1. Validate form fields
        $validatedData = $request->validate([
            // Pemohon
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:20'],
            'nim' => ['nullable', 'string', 'max:50'],
            'university' => ['required', 'string', 'max:255'],
            'faculty' => ['required', 'string', 'max:255'],
            'study_program' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            // Penelitian
            'title' => ['required', 'string', 'max:500'],
            'location' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            // Documents
            'proposal_penelitian' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'surat_pengantar_kampus' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ], [
            'proposal_penelitian.mimes' => 'Format Proposal Penelitian harus PDF.',
            'proposal_penelitian.max' => 'Ukuran Proposal Penelitian maksimal 2 MB.',
            'surat_pengantar_kampus.mimes' => 'Format Surat Pengantar Kampus harus PDF.',
            'surat_pengantar_kampus.max' => 'Ukuran Surat Pengantar Kampus maksimal 2 MB.',
        ]);

        return DB::transaction(function () use ($request, $validatedData) {
            // 2. Generate Registration Number: ERS-YYYY-XXXXXX
            $year = date('Y');
            
            // Get count of submissions created this year
            $count = Submission::whereYear('created_at', $year)->count();
            $nextNumber = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $registrationNumber = "ERS-{$year}-{$nextNumber}";

            // 3. Create Submission
            $submission = Submission::create([
                'registration_number' => $registrationNumber,
                'name' => $validatedData['name'],
                'gender' => $validatedData['gender'],
                'nim' => $validatedData['nim'] ?? null,
                'university' => $validatedData['university'],
                'faculty' => $validatedData['faculty'],
                'study_program' => $validatedData['study_program'],
                'semester' => $validatedData['semester'] ?? null,
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'title' => $validatedData['title'],
                'location' => $validatedData['location'],
                'purpose' => $validatedData['purpose'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'current_status' => 'Menunggu Verifikasi',
            ]);

            // 4. Handle File Uploads
            $documentTypes = [
                'surat_pengantar_kampus' => 'Surat Pengantar Kampus',
                'proposal_penelitian' => 'Proposal Penelitian'
            ];

            foreach ($documentTypes as $key => $label) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    
                    // Format file name: ERS-YYYY-XXXXXX_document_type.extension
                    $fileName = "{$registrationNumber}_" . Str::slug($key, '_') . ".{$extension}";
                    
                    // Store file
                    $path = $file->storeAs('submissions/' . $registrationNumber, $fileName, 'public');

                    // Save to database
                    Document::create([
                        'submission_id' => $submission->id,
                        'document_type' => $label,
                        'file_path' => $path,
                        'file_name' => $originalName,
                    ]);
                }
            }

            // 5. Create initial status log
            SubmissionStatus::create([
                'submission_id' => $submission->id,
                'status' => 'Menunggu Verifikasi',
                'notes' => 'Permohonan berhasil dikirim oleh pemohon.',
            ]);

            // 6. Send Email Notification
            try {
                \Illuminate\Support\Facades\Mail::to($submission->email)->send(new \App\Mail\SubmissionCreated($submission));
            } catch (\Exception $e) {
                // Log email error but don't fail the transaction
                \Illuminate\Support\Facades\Log::error('Failed to send SubmissionCreated email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Permohonan berhasil dikirim',
                'registration_number' => $registrationNumber
            ], 201);
        });
    }

    /**
     * Track status of an application.
     */
    public function track($registration_number)
    {
        $submission = Submission::where('registration_number', $registration_number)
            ->with(['statusLogs' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 'documents'])
            ->first();

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
        $submission = Submission::where('registration_number', $registration_number)->first();

        if (!$submission) {
            return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
        }

        if ($submission->current_status !== 'Disetujui' || !$submission->permit_file_path) {
            return response()->json(['message' => 'Surat izin belum tersedia atau permohonan belum disetujui.'], 400);
        }

        if (!Storage::disk('public')->exists($submission->permit_file_path)) {
            return response()->json(['message' => 'File surat izin tidak ditemukan di server.'], 404);
        }

        return Storage::disk('public')->download($submission->permit_file_path);
    }
}
