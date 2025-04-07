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
            'candidates.*.phone' => 'nullable|phone:AUTO,ID|string',
            'candidates.*.whatsapp' => 'nullable|string|max:20',
            'candidates.*.source' => 'nullable|string|max:100',
            'candidates.*.status' => 'nullable|in:active,hired,rejected,withdrawn|string', // updated validation
            'candidates.*.notes' => 'nullable|string|max:1000'
        ];
    }

    public function after($validator)
    {
        return [
            function (Validator $validator){
            if(isset($this->candidates) && is_array($this->candidates)){
                $emails = collect($this->candidates)->pluck('email');
                $duplicates = $emails->duplicates();

                if ($duplicates->count() > 0) {
                    $validator->errors()->add(
                        'candidates', 'Duplicate emails found in submitted batch: ' . $duplicates->implode(', ')
                    );
                }
            }
        }];
    }
}
