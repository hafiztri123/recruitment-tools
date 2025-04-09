<?php

namespace App\Domain\Interview\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_at' => [
                'required',
                'date',
                'after_or_equal:now',
                'before_or_equal:' . now()->addMonths(6)->format('Y-m-d H:i:s')
            ],
            'duration_minutes' => 'required|integer',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
            'interviewers' => 'sometimes|array|nullable',
            'interviewers.*' => 'integer|exists:users,id',
        ];
    }
}
