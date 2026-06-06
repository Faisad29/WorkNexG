<?php

namespace App\Domain\Employee\Http\Requests;

use App\Domain\Shared\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = app(TenantContext::class)->orgId();

        return [
            'site_id' => ['nullable', 'uuid', 'exists:sites,id'],
            'employee_code' => [
                'required', 'string', 'max:50',
                Rule::unique('employees', 'employee_code')->where('org_id', $companyId),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'salary_type' => ['required', 'in:monthly,daily,hourly,project_based'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'join_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date', 'after_or_equal:join_date'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }
}
