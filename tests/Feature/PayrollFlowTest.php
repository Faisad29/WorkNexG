<?php

namespace Tests\Feature;

use App\Domain\Attendance\Models\AttendanceRecord;
use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Payroll\Services\PayrollService;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Tests\TestCase;

class PayrollFlowTest extends TestCase
{
    public function test_payroll_generation_uses_attendance_aggregates(): void
    {
        $tenantContext = app(TenantContext::class);
        $company = Company::factory()->create();
        $tenantContext->setCompanyId($company->id);

        $site = Site::factory()->create(['org_id' => $company->id]);
        $employee = Employee::factory()->create(['org_id' => $company->id, 'site_id' => $site->id, 'base_salary' => 6000]);

        $startDate = now()->startOfMonth();

        AttendanceRecord::factory()->count(5)->sequence(
            fn ($sequence) => [
                'attendance_date' => $startDate->copy()->addDays($sequence->index + 1)->toDateString(),
            ]
        )->create([
            'org_id' => $company->id,
            'employee_id' => $employee->id,
            'site_id' => $site->id,
            'status' => 'present',
            'work_hours' => 8,
        ]);

        $payroll = app(PayrollService::class)->generate([
            'month' => (int) now()->month,
            'year' => (int) now()->year,
        ]);

        $this->assertSame(1, $payroll->total_employees);
        $this->assertSame('generated', $payroll->status);
    }
}
