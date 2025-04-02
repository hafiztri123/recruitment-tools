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
            'email' => ['string', 'email', 'required', 'unique:candidates,email'],
            'phone' => ['string', 'max:20', 'sometimes'],
            'whatsapp' => ['string', 'max:20', 'sometimes'],
            'resume_path' => ['string', 'max:255', 'sometimes'],
            'source' => ['string', 'max:100', 'sometimes'],
            'status' => ['string', 'in:active,hired,rejected,withdrawn', 'sometimes'],
            'notes' => ['string', 'sometimes'],
        ];
    }
}
