<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Billing\Models\Plan;
use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Shared\TenantContext;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DocumentAndSubscriptionApiTest extends TestCase
{
    public function test_document_and_subscription_endpoints_work(): void
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['org_id' => $company->id]);
        $plan = Plan::factory()->create();
        $user = User::factory()->create(['org_id' => $company->id, 'user_type' => 'tenant', 'role' => 'admin']);

        app(TenantContext::class)->setCompanyId($company->id);
        Sanctum::actingAs($user);

        $documentResponse = $this->postJson('/api/documents', [
            'employee_id' => $employee->id,
            'type' => 'iqama',
            'document_number' => '123456',
            'expiry_date' => now()->addMonths(6)->toDateString(),
            'metadata' => ['source' => 'api-test'],
        ]);

        $documentResponse->assertCreated();

        $subscriptionResponse = $this->postJson('/api/subscriptions', [
            'plan_id' => $plan->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $subscriptionResponse->assertCreated();
    }
}
