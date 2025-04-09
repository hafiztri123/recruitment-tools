<?php

namespace App\Domain\Candidate\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'candidates.*.email' => 'required|email:rfc,dns|unique:candidates,email|max:255',
            'candidates.*.phone' => 'nullable|string|max:20',
            'candidates.*.whatsapp' => 'nullable|string|max:20',
            'candidates.*.resume_path' => 'nullable|string|max:255',
            'candidates.*.source' => 'nullable|string|max:100',
            'candidates.*.status' => 'required|in:active,hired,rejected,withdrawn|string',
            'candidates.*.notes' => 'nullable|string|max:1000'
        ];
    }

    public function after($validator)
    {
        return [
            function (Validator $validator) {
                if (isset($this->candidates) && is_array($this->candidates)) {
                    $emails = collect($this->candidates)->pluck('email');
                    $duplicates = $emails->duplicates();

                    if ($duplicates->count() > 0) {
                        $validator->errors()->add(
                            'candidates',
                            'Duplicate emails found in submitted batch: ' . $duplicates->implode(', ')
                        );
                    }
                }
            }
        ];
    }
}
