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

            // Register custom university if not exists
            $universityName = trim($validatedData['university']);
            if (!empty($universityName)) {
                $exists = \App\Models\University::where('name', $universityName)->exists();
                if (!$exists) {
                    \App\Models\University::create([
                        'name' => $universityName,
                        'is_approved' => false,
                    ]);
                }
            }

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

            // Register custom university if not exists
            $universityName = trim($validated['asal_universitas']);
            if (!empty($universityName)) {
                $exists = \App\Models\University::where('name', $universityName)->exists();
                if (!$exists) {
                    \App\Models\University::create([
                        'name' => $universityName,
                        'is_approved' => false,
                    ]);
                }
            }

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
            $isPt = $submission->isPt();

            if ($validated['status'] === 'Menentukan Jadwal Wawancara' && $isPt) {
                $submission->interview_date = $validated['interview_date'] ?? null;
                $submission->save();
            }
            
            // Generate letter automatically if status is Sedang Diproses (PN)
            if ($validated['status'] === 'Sedang Diproses' && !$isPt) {
                $recipientPosition = $validated['recipient_position'] ?? null;
                if ($recipientPosition === 'Lainnya') {
                    $recipientPosition = $validated['custom_recipient_position'] ?? null;
                }
                if ($recipientPosition) {
                    $submission->recipient_position = $recipientPosition;
                }

                $destinationCity = $validated['destination_city'] ?? null;
                if ($destinationCity) {
                    $submission->destination_city = $destinationCity;
                }

                if ($recipientPosition || $destinationCity) {
                    $submission->save();
                }
                $this->letterService->generateLetter($submission, $validated['panitera_id'], $validated['letter_date']);
            }

            // Generate letter automatically if status is Pembuatan Surat Keterangan Riset (PT)
            if ($validated['status'] === 'Pembuatan Surat Keterangan Riset' && $isPt) {
                $konsentrasi = $validated['konsentrasi'] ?? null;
                if ($konsentrasi === 'Lainnya') {
                    $konsentrasi = $validated['custom_konsentrasi'] ?? null;
                }
                if ($konsentrasi) {
                    $submission->konsentrasi = $konsentrasi;
                }

                $submission->start_date = $validated['start_date'] ?? $submission->start_date;
                $submission->end_date = $validated['end_date'] ?? $submission->end_date;
                $submission->hakim_id = $validated['hakim_id'] ?? null;
                $submission->save();

                $this->letterService->generateLetter($submission, $validated['panitera_id'], $validated['letter_date']);

                // Send email to Hakim
                if ($submission->hakim) {
                    try {
                        Mail::to($submission->hakim->email_hakim)->send(new \App\Mail\HakimNotificationMail($submission));
                    } catch (\Exception $e) {
                        Log::error('Failed to send HakimNotificationMail: ' . $e->getMessage());
                    }
                }
            }

            // Save the uploaded permit file if status is Disetujui, Pembuatan Surat Keterangan Riset, or Ditolak
            if (in_array($validated['status'], ['Disetujui', 'Pembuatan Surat Keterangan Riset', 'Ditolak']) && $permitFile) {
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

            // Send Email Notification to Applicant
            try {
                Mail::to($submission->email)->send(new SubmissionStatusUpdated($submission));
            } catch (\Exception $e) {
                Log::error('Failed to send SubmissionStatusUpdated email: ' . $e->getMessage());
            }

            return $submission;
        });
    }
}
