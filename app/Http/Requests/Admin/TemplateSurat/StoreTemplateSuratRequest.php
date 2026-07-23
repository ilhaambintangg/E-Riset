<?php

namespace App\Http\Requests\Admin\TemplateSurat;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateSuratRequest extends FormRequest
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
            'template' => 'required|file|mimes:docx|max:5120',
            'institution_type' => 'required|string|in:PT,PN',
            'template_type' => 'required|string|in:individu,kelompok',
        ];
    }
}
