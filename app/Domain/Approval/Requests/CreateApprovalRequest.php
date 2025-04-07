<?php

namespace App\Domain\Approval\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'approver_id' => 'required|exists:users,id|string',
            'comments' => 'nullable|string'
        ];
    }
}
