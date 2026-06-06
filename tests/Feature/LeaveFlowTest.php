<?php

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Leave\Services\LeaveService;
use App\Domain\Shared\TenantContext;
use Tests\TestCase;

class LeaveFlowTest extends TestCase
{
    public function test_leave_request_can_be_created(): void
    {
        $tenantContext = app(TenantContext::class);
        $company = Company::factory()->create();
        $tenantContext->setCompanyId($company->id);

        $employee = Employee::factory()->create(['org_id' => $company->id]);

        $leave = app(LeaveService::class)->requestLeave([
            'employee_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'reason' => 'Vacation',
        ]);

        $this->assertSame('pending', $leave->status);
        $this->assertSame($employee->id, $leave->employee_id);
    }
}
