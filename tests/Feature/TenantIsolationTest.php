<?php

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Shared\TenantContext;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    public function test_employee_queries_are_scoped_to_tenant(): void
    {
        $tenantContext = app(TenantContext::class);
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $tenantContext->setCompanyId($companyA->id);

        Employee::factory()->create(['org_id' => $companyA->id]);
        Employee::factory()->create(['org_id' => $companyB->id]);

        $this->assertCount(1, Employee::query()->get());
    }
}
