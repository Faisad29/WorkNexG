<?php

namespace App\Domain\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'uuid', 'exists:employees,id'],
            'attendance_date' => ['required', 'date'],
            'check_out_time' => ['required', 'date'],
            'check_out_latitude' => ['required', 'numeric'],
            'check_out_longitude' => ['required', 'numeric'],
            'work_hours' => ['nullable', 'numeric', 'min:0'],
            'overtime_hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:present,absent,late,half_day,overtime,adjusted,rejected'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
        ];
    }
}
