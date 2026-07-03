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
        
        $permitFileRule = ['nullable', 'file', 'mimes:pdf', 'max:5120'];
        if ($this->status === 'Disetujui' && (!$submission || !$submission->permit_file_path)) {
            $permitFileRule = ['required', 'file', 'mimes:pdf', 'max:5120'];
        }

        return [
            'status' => ['required', 'string', 'in:Menunggu Verifikasi,Sedang Diproses,Disetujui,Ditolak'],
            'notes' => [$this->status === 'Ditolak' ? 'required' : 'nullable', 'string'],
            'panitera_id' => [$this->status === 'Sedang Diproses' ? 'required' : 'nullable', 'exists:panitera,id'],
            'letter_date' => [$this->status === 'Sedang Diproses' ? 'required' : 'nullable', 'date'],
            'permit_file' => $permitFileRule,
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'notes.required' => 'Catatan wajib diisi saat menolak permohonan.',
            'panitera_id.required' => 'Panitera harus dipilih saat memproses permohonan untuk generate surat izin.',
            'letter_date.required' => 'Tanggal surat harus diisi saat memproses permohonan untuk generate surat izin.',
            'permit_file.required' => 'Surat izin (PDF yang sudah ditandatangani) harus diunggah saat menyetujui permohonan.',
        ];
    }
}
