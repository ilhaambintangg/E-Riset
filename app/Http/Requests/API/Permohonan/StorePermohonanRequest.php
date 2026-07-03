<?php

namespace App\Http\Requests\API\Permohonan;

use Illuminate\Foundation\Http\FormRequest;

class StorePermohonanRequest extends FormRequest
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
        return [
            'nama_lengkap'        => ['required', 'string', 'max:255'],
            'jenis_kelamin'       => ['required', 'in:Laki-laki,Perempuan'],
            'email'               => ['required', 'email', 'max:255'],
            'no_hp'               => ['required', 'string', 'max:20'],
            'asal_universitas'    => ['required', 'string', 'max:255'],
            'surat_pengantar'     => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'proposal_penelitian' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
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
        ];
    }
}
