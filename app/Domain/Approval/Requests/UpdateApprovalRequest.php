<?php

namespace App\Domain\Approval\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected|string',
            'comments' => 'nullable|string'
        ];
    }
}
