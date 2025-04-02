<?php

namespace App\Domain\Candidate\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMultipleCandidatesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidates' => 'required|array|min:1',
            'candidates.*.first_name' => 'required|string|max:255',
            'candidates.*.last_name' => 'required|string|max:255',
            'candidates.*.email' => 'required|email|unique:candidates,email',
            'candidates.*.phone' => 'nullable|string|max:20',
            'candidates.*.whatsapp' => 'nullable|string|max:20',
            'candidates.*.source' => 'nullable|string|max:100',
            'candidates.*.status' => 'nullable|in:active,hired,rejected,withdrawn', // updated validation
            'candidates.*.notes' => 'nullable|string'
        ];
    }
}
