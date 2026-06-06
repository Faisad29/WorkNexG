<?php

namespace Tests\Feature;

use App\Domain\Attendance\Services\AttendanceService;
use App\Domain\Company\Models\Company;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use App\Domain\Employee\Models\Employee;
use Tests\TestCase;

class AttendanceFlowTest extends TestCase
{
    public function test_check_in_creates_attendance_record(): void
    {
        $tenantContext = app(TenantContext::class);
        $company = Company::factory()->create();
        $tenantContext->setCompanyId($company->id);

        $site = Site::factory()->create(['org_id' => $company->id]);
        $employee = Employee::factory()->create(['org_id' => $company->id, 'site_id' => $site->id]);

        $record = app(AttendanceService::class)->checkIn([
            'employee_id' => $employee->id,
            'site_id' => $site->id,
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now(),
            'check_in_latitude' => 24.7136,
            'check_in_longitude' => 46.6753,
            'is_manual_override' => false,
        ]);

        $this->assertSame($employee->id, $record->employee_id);
        $this->assertSame('present', $record->status);
    }
}
