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
            'scheduled_at' => 'required|date|after_or_equal:now()',
            'duration_minutes' => 'required|integer',
            'location' => 'sometimes|string|max:255',
            'meeting_link' => 'sometimes|string|max:255',
            'notes' => 'sometimes|string|max:255',
        ];
    }
}
