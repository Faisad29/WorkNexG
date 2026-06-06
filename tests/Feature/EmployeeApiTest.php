<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    public function test_employee_can_be_created_via_api(): void
    {
        $company = Company::factory()->create();
        $site = Site::factory()->create(['org_id' => $company->id]);
        $user = User::factory()->create(['org_id' => $company->id, 'user_type' => 'tenant', 'role' => 'hr']);

        app(TenantContext::class)->setCompanyId($company->id);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/employees', [
            'site_id' => $site->id,
            'employee_code' => 'EMP-1001',
            'full_name' => 'Test Employee',
            'salary_type' => 'monthly',
            'base_salary' => 6000,
            'status' => 'active',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.employee_code', 'EMP-1001');
    }
}
