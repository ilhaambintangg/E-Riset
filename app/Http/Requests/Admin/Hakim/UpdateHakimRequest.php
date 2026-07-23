<?php

namespace App\Http\Requests\Admin\Hakim;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHakimRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('hakim');
        return [
            'nama_hakim' => ['required', 'string', 'max:255'],
            'email_hakim' => ['required', 'email', 'max:255', 'unique:hakims,email_hakim,' . $id],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_hakim.required' => 'Nama hakim wajib diisi.',
            'email_hakim.required' => 'Email hakim wajib diisi.',
            'email_hakim.email' => 'Format email tidak valid.',
            'email_hakim.unique' => 'Email hakim ini sudah terdaftar.',
        ];
    }
}
