<?php

namespace App\Http\Requests\Admin\Requirement;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequirementRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
        ];
    }
}
