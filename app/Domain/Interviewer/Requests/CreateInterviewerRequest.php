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
            'feedback_submitted' => 'nullable|boolean',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5'
        ];
    }
}
