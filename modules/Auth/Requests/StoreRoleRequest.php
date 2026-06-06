<?php

namespace Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'org_id' => ['nullable', 'uuid', 'exists:organizations,id'],
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_system' => ['sometimes', 'boolean'],
        ];
    }
}
