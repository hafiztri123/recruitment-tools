<?php

namespace App\Domain\Interviewer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInterviewerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'feedback_submitted' => 'boolean|nullable',
            'feedback' => 'string|nullable',
            'rating' => 'integer|between:1,5|nullable'
        ];
    }
}
