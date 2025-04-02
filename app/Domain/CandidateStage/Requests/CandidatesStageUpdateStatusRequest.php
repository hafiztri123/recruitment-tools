<?php

namespace App\Domain\CandidateStage\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidatesStageUpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidates' => 'required|array|min:1',
            'candidates.*' => 'required|integer|exists:candidates,id'
        ];
    }
}
