<?php

namespace App\Domain\Candidate\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCandidateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'max:255', 'required'],
            'last_name' => ['string', 'max:255', 'required'],
            'email' => ['string', 'email:rfc,dns', 'required', 'unique:candidates,email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'resume_path' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:100'],
            'status' => ['string', 'in:active,hired,rejected,withdrawn', 'required'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
