<?php

namespace App\Http\Requests\Admin\WebSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebSettingRequest extends FormRequest
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
            'nama_instansi' => 'required|string|max:255',
            'letter_code' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'google_maps' => 'nullable|string',
            'link_terkait' => 'nullable|array',
        ];
    }
}
