<?php

namespace Tests\Feature;

use Tests\TestCase;

class TenantRegistrationApiTest extends TestCase
{
    public function test_tenant_registration_creates_company_and_user(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'company_name' => 'Acme KSA',
            'name' => 'Owner Admin',
            'email' => 'owner@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.company.name', 'Acme KSA');
        $response->assertJsonPath('data.user.email', 'owner@example.test');
    }
}
