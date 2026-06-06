<?php

namespace App\Domain\Compliance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'type' => ['required', 'in:iqama,visa,passport,contract,certificate'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'file_url' => ['nullable', 'string'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
