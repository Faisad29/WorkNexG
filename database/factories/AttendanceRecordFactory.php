<?php

namespace Database\Factories;

use App\Domain\Attendance\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'employee_id' => \App\Domain\Employee\Models\Employee::factory(),
            'site_id' => \App\Domain\Site\Models\Site::factory(),
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now()->subHours(8),
            'check_out_time' => now(),
            'check_in_latitude' => 24.7136,
            'check_in_longitude' => 46.6753,
            'check_out_latitude' => 24.7136,
            'check_out_longitude' => 46.6753,
            'status' => 'present',
            'work_hours' => 8,
            'overtime_hours' => 0,
            'is_manual_override' => false,
            'sync_status' => 'synced',
        ];
    }
}
