<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AttendanceApiTest extends TestCase
{
    public function test_attendance_check_in_can_be_created_via_api(): void
    {
        $company = Company::factory()->create();
        $site = Site::factory()->create(['org_id' => $company->id]);
        $employee = Employee::factory()->create(['org_id' => $company->id, 'site_id' => $site->id]);
        $user = User::factory()->create(['org_id' => $company->id, 'user_type' => 'tenant', 'role' => 'employee']);

        app(TenantContext::class)->setCompanyId($company->id);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/attendance/check-in', [
            'employee_id' => $employee->id,
            'site_id' => $site->id,
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now()->toIso8601String(),
            'check_in_latitude' => 24.7136,
            'check_in_longitude' => 46.6753,
            'is_manual_override' => false,
        ]);

        $response->assertCreated();
    }
}
