<?php

namespace App\Domain\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_name' => ['sometimes', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'organization_code' => ['sometimes', 'nullable', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['sometimes', 'string', 'max:100'],
            'timezone' => ['sometimes', 'string', 'max:50'],
            'role' => ['sometimes', 'in:admin,hr,supervisor,employee,platform-admin,support-engineer,hr-manager'],
        ];
    }
}
