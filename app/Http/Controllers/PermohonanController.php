<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Document;
use App\Models\SubmissionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PermohonanController extends Controller
{
    /**
     * Simpan permohonan penelitian baru (Simplified Form).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'        => ['required', 'string', 'max:255'],
            'jenis_kelamin'       => ['required', 'in:Laki-laki,Perempuan'],
            'email'               => ['required', 'email', 'max:255'],
            'no_hp'               => ['required', 'string', 'max:20'],
            'asal_universitas'    => ['required', 'string', 'max:255'],
            'surat_pengantar'     => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proposal_penelitian' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'nama_lengkap.required'        => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'             => 'Jenis kelamin tidak valid.',
            'email.required'               => 'Alamat email wajib diisi.',
            'email.email'                  => 'Format email tidak valid.',
            'no_hp.required'               => 'Nomor HP wajib diisi.',
            'asal_universitas.required'    => 'Asal universitas wajib diisi.',
            'surat_pengantar.required'     => 'Surat pengantar wajib diunggah.',
            'surat_pengantar.mimes'        => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max'          => 'Surat pengantar maksimal 5 MB.',
            'proposal_penelitian.required' => 'Proposal penelitian wajib diunggah.',
            'proposal_penelitian.mimes'    => 'Proposal penelitian harus berformat PDF.',
            'proposal_penelitian.max'      => 'Proposal penelitian maksimal 5 MB.',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $year = date('Y');
            
            // Get count of submissions created this year
            $count = Submission::whereYear('created_at', $year)->count();
            $nextNumber = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
            $registrationNumber = "ERS-{$year}-{$nextNumber}";

            // Simpan ke database Submission
            $submission = Submission::create([
                'registration_number' => $registrationNumber,
                'name'                => $validated['nama_lengkap'],
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

            // Format file name
            $fileSurat = $request->file('surat_pengantar');
            $fileProposal = $request->file('proposal_penelitian');

            $suratName = "{$registrationNumber}_surat_pengantar." . $fileSurat->getClientOriginalExtension();
            $proposalName = "{$registrationNumber}_proposal_penelitian." . $fileProposal->getClientOriginalExtension();

            $suratPath = $fileSurat->storeAs('submissions/' . $registrationNumber, $suratName, 'public');
            $proposalPath = $fileProposal->storeAs('submissions/' . $registrationNumber, $proposalName, 'public');

            // Save documents
            Document::create([
                'submission_id' => $submission->id,
                'document_type' => 'Surat Pengantar Kampus',
                'file_path'     => $suratPath,
                'file_name'     => $fileSurat->getClientOriginalName(),
            ]);

            Document::create([
                'submission_id' => $submission->id,
                'document_type' => 'Proposal Penelitian',
                'file_path'     => $proposalPath,
                'file_name'     => $fileProposal->getClientOriginalName(),
            ]);

            // Create log
            SubmissionStatus::create([
                'submission_id' => $submission->id,
                'status'        => 'Menunggu Verifikasi',
                'notes'         => 'Permohonan berhasil dikirim oleh pemohon.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin penelitian berhasil dikirim. Silakan menunggu proses verifikasi dari Pengadilan Tinggi Tanjungkarang.',
                'registration_number' => $registrationNumber,
                'data'    => $submission,
            ], 201);
        });
    }
}
