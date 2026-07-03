<?php

namespace App\Http\Requests\API\Submission;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
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
            // Pemohon
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:50'],
            'university' => ['required', 'string', 'max:255'],
            'faculty' => ['required', 'string', 'max:255'],
            'study_program' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            
            // New fields for letter generation
            'recipient_position' => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:255'],
            'reference_letter_number' => ['required', 'string', 'max:255'],
            'reference_letter_date' => ['required', 'date'],
            'research_title' => ['required', 'string'],
            'research_location' => ['required', 'string', 'max:255'],
            'research_type' => ['required', 'string', 'max:255'],
            
            // Other fields
            'purpose' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            
            // Members
            'is_group' => ['required', 'string', 'in:individu,kelompok'],
            'members' => ['nullable', 'array'],
            'members.*.name' => ['required_if:is_group,kelompok', 'string', 'max:255'],
            'members.*.npm' => ['required_if:is_group,kelompok', 'string', 'max:50'],
            
            // Documents (2 MB limit)
            'proposal_penelitian' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'surat_pengantar_kampus' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'proposal_penelitian.mimes' => 'File harus berformat PDF.',
            'proposal_penelitian.max' => 'Ukuran file maksimal 2 MB.',
            'surat_pengantar_kampus.mimes' => 'File harus berformat PDF.',
            'surat_pengantar_kampus.max' => 'Ukuran file maksimal 2 MB.',
        ];
    }
}
