<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateCreationRequest extends FormRequest
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
            'email' => ['string', 'email','unique:candidates,email', 'required'],
            'phone' => ['sometimes','string', 'max_digits:20'],
            'whatsapp' => ['sometimes','string', 'max_digits:20'],
            'resume_path' => ['sometimes','string', 'max:255'],
            'source' => ['sometimes','string', 'max:100'],
            'status' => ["in:'active', 'hired', 'rejected', 'withdrawn'"],
            'notes' => ['string'],
        ];
    }
}
