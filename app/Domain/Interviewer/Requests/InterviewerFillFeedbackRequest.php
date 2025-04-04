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
            'feedback' => 'sometimes|string',
            'rating' => 'sometimes|integer|between:1,10'
        ];
    }
}
