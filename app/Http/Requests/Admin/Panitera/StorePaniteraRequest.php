<?php

namespace App\Http\Requests\Admin\Panitera;

use Illuminate\Foundation\Http\FormRequest;

class StorePaniteraRequest extends FormRequest
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
            'nama_panitera' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255|unique:panitera',
            'jabatan' => 'required|string|max:255',
            'status_aktif' => 'boolean',
        ];
    }
}
