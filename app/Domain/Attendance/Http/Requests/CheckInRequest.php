<?php

namespace App\Domain\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'site_id' => ['required', 'uuid', 'exists:sites,id'],
            'attendance_date' => ['required', 'date'],
            'check_in_time' => ['required', 'date'],
            'check_in_latitude' => ['required', 'numeric'],
            'check_in_longitude' => ['required', 'numeric'],
            'device_id' => ['nullable', 'string', 'max:100'],
            'source' => ['nullable', 'string', 'max:30'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
            'override_request_id' => ['nullable', 'uuid', 'exists:attendance_override_requests,id'],
        ];
    }
}

