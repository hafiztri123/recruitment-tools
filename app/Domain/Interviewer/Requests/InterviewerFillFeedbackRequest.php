<?php

namespace App\Domain\Interviewer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InterviewerFillFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,10'
        ];
    }
}
