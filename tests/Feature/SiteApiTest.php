<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Shared\TenantContext;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SiteApiTest extends TestCase
{
    public function test_site_can_be_created_via_api(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['org_id' => $company->id, 'user_type' => 'tenant', 'role' => 'admin']);

        app(TenantContext::class)->setCompanyId($company->id);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/sites', [
            'name' => 'Main Site',
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'radius_meters' => 150,
            'is_active' => true,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'Main Site');
    }
}
