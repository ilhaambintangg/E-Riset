<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Document;
use App\Models\SubmissionStatus;
use App\Services\LetterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\SubmissionCreated;
use App\Mail\SubmissionStatusUpdated;

class SubmissionService
{
    protected $letterService;

    public function __construct(LetterService $letterService)
    {
        $this->letterService = $letterService;
    }

    /**
     * Create a standard submission.
     */
    public function createSubmission(array $validatedData, array $files): Submission
    {
        return DB::transaction(function () use ($validatedData, $files) {
            $year = date('Y');
            
            // Get count of submissions created this year
            $count = Submission::whereYear('created_at', $year)->count();
            $nextNumber = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
            $registrationNumber = "ERS-{$year}-{$nextNumber}";

            // Create Submission
            $submission = Submission::create([
                'registration_number' => $registrationNumber,
                'name' => $validatedData['name'],
                'nim' => $validatedData['nim'],
                'university' => $validatedData['university'],
                'faculty' => $validatedData['faculty'],
                'study_program' => $validatedData['study_program'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                
                // Fields for letter generation
                'recipient_position' => $validatedData['recipient_position'],
                'destination_city' => $validatedData['destination_city'],
                'reference_letter_number' => $validatedData['reference_letter_number'],
                'reference_letter_date' => $validatedData['reference_letter_date'],
                'research_title' => $validatedData['research_title'],
                'research_location' => $validatedData['research_location'],
                'research_type' => $validatedData['research_type'],
                
                // Compatibility mapping
                'title' => $validatedData['research_title'],
                'location' => $validatedData['research_location'],
                
                'purpose' => $validatedData['purpose'],
                'start_date' => $validatedData['start_date'] ?? date('Y-m-d'),
                'end_date' => $validatedData['end_date'] ?? date('Y-m-d', strtotime('+1 month')),
                'current_status' => 'Menunggu Verifikasi',
            ]);

            // Save members if it's a group submission
            if ($validatedData['is_group'] === 'kelompok' && !empty($validatedData['members'])) {
                foreach ($validatedData['members'] as $member) {
                    if (!empty($member['name']) && !empty($member['npm'])) {
                        $submission->members()->create([
                            'member_name' => $member['name'],
                            'member_npm' => $member['npm'],
                        ]);
                    }
                }
            }

            // Handle File Uploads
            $documentTypes = [
                'surat_pengantar_kampus' => 'Surat Pengantar Kampus',
                'proposal_penelitian' => 'Proposal Penelitian'
            ];

            foreach ($documentTypes as $key => $label) {
                if (isset($files[$key])) {
                    $file = $files[$key];
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

            // Create initial status log
            SubmissionStatus::create([
                'submission_id' => $submission->id,
                'status' => 'Menunggu Verifikasi',
                'notes' => 'Permohonan berhasil dikirim oleh pemohon.',
            ]);

            // Send Email Notification
            try {
                Mail::to($submission->email)->send(new SubmissionCreated($submission));
            } catch (\Exception $e) {
                Log::error('Failed to send SubmissionCreated email: ' . $e->getMessage());
            }

            return $submission;
        });
    }

    /**
     * Create a simplified permohonan.
     */
    public function createSimplifiedPermohonan(array $validated, array $files): Submission
    {
        return DB::transaction(function () use ($validated, $files) {
            $year = date('Y');
            
            // Get count of submissions created this year
            $count = Submission::whereYear('created_at', $year)->count();
            $nextNumber = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
            $registrationNumber = "ERS-{$year}-{$nextNumber}";

            // Simpan ke database Submission
            $submission = Submission::create([
                'registration_number' => $registrationNumber,
                'name'                => $validated['nama_lengkap'],
                'gender'              => $validated['jenis_kelamin'] ?? null,
                'nim'                 => null,
                'university'          => $validated['asal_universitas'],
                'faculty'             => '-', // Default since not in form
                'study_program'       => '-', // Default since not in form
                'email'               => $validated['email'],
                'phone'               => $validated['no_hp'],
                'address'             => '-', // Default since not in form
                'title'               => 'Penelitian oleh ' . $validated['nama_lengkap'],
                'target_institution'  => 'Pengadilan Tinggi Tanjungkarang',
                'purpose'             => '-',
                'methodology'         => '-',
                'start_date'          => date('Y-m-d'),
                'end_date'            => date('Y-m-d', strtotime('+1 month')),
                'current_status'      => 'Menunggu Verifikasi',
            ]);

            // Handle file uploads
            $fileSurat = $files['surat_pengantar'] ?? null;
            $fileProposal = $files['proposal_penelitian'] ?? null;

            if ($fileSurat) {
                $suratName = "{$registrationNumber}_surat_pengantar." . $fileSurat->getClientOriginalExtension();
                $suratPath = $fileSurat->storeAs('submissions/' . $registrationNumber, $suratName, 'public');
                Document::create([
                    'submission_id' => $submission->id,
                    'document_type' => 'Surat Pengantar Kampus',
                    'file_path'     => $suratPath,
                    'file_name'     => $fileSurat->getClientOriginalName(),
                ]);
            }

            if ($fileProposal) {
                $proposalName = "{$registrationNumber}_proposal_penelitian." . $fileProposal->getClientOriginalExtension();
                $proposalPath = $fileProposal->storeAs('submissions/' . $registrationNumber, $proposalName, 'public');
                Document::create([
                    'submission_id' => $submission->id,
                    'document_type' => 'Proposal Penelitian',
                    'file_path'     => $proposalPath,
                    'file_name'     => $fileProposal->getClientOriginalName(),
                ]);
            }

            // Create log
            SubmissionStatus::create([
                'submission_id' => $submission->id,
                'status'        => 'Menunggu Verifikasi',
                'notes'         => 'Permohonan berhasil dikirim oleh pemohon.',
            ]);

            return $submission;
        });
    }

    /**
     * Update submission status.
     */
    public function updateStatus(Submission $submission, array $validated, $permitFile = null): Submission
    {
        return DB::transaction(function () use ($submission, $validated, $permitFile) {
            $admin = Auth::user();
            
            // Generate letter automatically if status is Sedang Diproses
            if ($validated['status'] === 'Sedang Diproses') {
                $this->letterService->generateLetter($submission, $validated['panitera_id'], $validated['letter_date']);
            }

            // Save the uploaded permit file if status is Disetujui
            if ($validated['status'] === 'Disetujui' && $permitFile) {
                $fileName = "Izin_Penelitian_{$submission->registration_number}." . $permitFile->getClientOriginalExtension();
                $path = $permitFile->storeAs('permits/' . $submission->registration_number, $fileName, 'public');
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
                Mail::to($submission->email)->send(new SubmissionStatusUpdated($submission));
            } catch (\Exception $e) {
                Log::error('Failed to send SubmissionStatusUpdated email: ' . $e->getMessage());
            }

            return $submission;
        });
    }
}
