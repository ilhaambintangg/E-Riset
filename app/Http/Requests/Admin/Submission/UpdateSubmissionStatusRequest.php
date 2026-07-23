<?php

namespace App\Http\Requests\Admin\Submission;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $submissionId = $this->route('id');
        $submission = Submission::find($submissionId);
        $isPt = $submission ? $submission->isPt() : false;
        
        $permitFileRule = ['nullable', 'file', 'mimes:pdf', 'max:2048'];
        if ($this->status === 'Disetujui' && (!$submission || !$submission->permit_file_path)) {
            $permitFileRule = ['required', 'file', 'mimes:pdf', 'max:2048'];
        }

        $allowedStatuses = $isPt
            ? 'Menunggu Verifikasi,Menentukan Jadwal Wawancara,Pembuatan Surat Keterangan Riset,Disetujui,Ditolak'
            : 'Menunggu Verifikasi,Sedang Diproses,Disetujui,Ditolak';

        $statusRule = [
            'required',
            'string',
            'in:' . $allowedStatuses,
        ];

        if ($submission) {
            $statusRule[] = function ($attribute, $value, $fail) use ($submission, $isPt) {
                $currentStatus = $submission->current_status;

                $allowed = [];
                if ($isPt) {
                    if ($currentStatus === 'Menunggu Verifikasi') {
                        $allowed = ['Menentukan Jadwal Wawancara', 'Ditolak'];
                    } elseif ($currentStatus === 'Menentukan Jadwal Wawancara') {
                        $allowed = ['Pembuatan Surat Keterangan Riset', 'Ditolak'];
                    } elseif ($currentStatus === 'Pembuatan Surat Keterangan Riset') {
                        $allowed = ['Disetujui', 'Ditolak'];
                    } elseif ($currentStatus === 'Disetujui') {
                        $allowed = ['Disetujui'];
                    } elseif ($currentStatus === 'Ditolak') {
                        $allowed = ['Ditolak'];
                    }
                } else {
                    if ($currentStatus === 'Menunggu Verifikasi') {
                        $allowed = ['Sedang Diproses', 'Ditolak'];
                    } elseif ($currentStatus === 'Sedang Diproses') {
                        $allowed = ['Disetujui', 'Ditolak'];
                    } elseif ($currentStatus === 'Disetujui') {
                        $allowed = ['Disetujui'];
                    } elseif ($currentStatus === 'Ditolak') {
                        $allowed = ['Ditolak'];
                    }
                }

                if (!in_array($value, $allowed)) {
                    $fail('Status permohonan tidak dapat dikembalikan ke tahap sebelumnya atau tidak valid untuk alur pengadilan ini.');
                }
            };
        }

        return [
            'status' => $statusRule,
            'notes' => [$this->status === 'Ditolak' ? 'required' : 'nullable', 'string'],
            'panitera_id' => [($this->status === 'Sedang Diproses' || $this->status === 'Pembuatan Surat Keterangan Riset') ? 'required' : 'nullable', 'exists:panitera,id'],
            'letter_date' => [($this->status === 'Sedang Diproses' || $this->status === 'Pembuatan Surat Keterangan Riset') ? 'required' : 'nullable', 'date'],
            'recipient_position' => ['nullable', 'string', 'max:255'],
            'custom_recipient_position' => ['nullable', 'string', 'max:255'],
            'destination_city' => ['nullable', 'string', 'max:255'],
            'permit_file' => $permitFileRule,

            'konsentrasi' => [($this->status === 'Pembuatan Surat Keterangan Riset') ? 'required' : 'nullable', 'string', 'max:255'],
            'custom_konsentrasi' => ['nullable', 'string', 'max:255'],
            'start_date' => [($this->status === 'Pembuatan Surat Keterangan Riset') ? 'required' : 'nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'hakim_id' => [($this->status === 'Pembuatan Surat Keterangan Riset') ? 'required' : 'nullable', 'exists:hakims,id'],
            'interview_date' => [($this->status === 'Menentukan Jadwal Wawancara') ? 'required' : 'nullable', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'notes.required' => 'Catatan wajib diisi saat menolak permohonan.',
            'panitera_id.required' => 'Panitera/Penandatangan harus dipilih.',
            'letter_date.required' => 'Tanggal surat harus diisi.',
            'permit_file.required' => 'File harus berformat PDF dengan ukuran maksimal 2 MB.',
            'permit_file.mimes' => 'File harus berformat PDF dengan ukuran maksimal 2 MB.',
            'permit_file.max' => 'File harus berformat PDF dengan ukuran maksimal 2 MB.',
            'konsentrasi.required' => 'Konsentrasi harus diisi.',
            'start_date.required' => 'Tanggal awal penelitian harus diisi.',
            'hakim_id.required' => 'Hakim pendamping harus dipilih.',
            'interview_date.required' => 'Tanggal dan waktu wawancara harus diisi.',
        ];
    }
}
