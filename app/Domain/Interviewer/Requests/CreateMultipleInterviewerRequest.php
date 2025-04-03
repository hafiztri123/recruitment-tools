<?php

namespace App\Domain\Interviewer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMultipleInterviewerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'interviewers' => 'required|array|min:1',
            'interviewers.*'=> 'required|exists:users,id'
        ];
    }
}
