<?php

namespace App\Domain\RecruitmentBatch\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRecruitmentBatchRequest extends FormRequest
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
            'name' => ['string', 'max:255', 'required'],
            'start_date' => ['date', 'after_or_equal:now()', 'required'],
            'end_date' => ['date', 'after_or_equal:start_date', 'sometimes'],
            'status' => ['string', 'in:active,completed,cancelled', 'sometimes'],
            'description' => ['string', 'sometimes']
        ];
    }
}
