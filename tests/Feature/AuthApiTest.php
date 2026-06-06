<?php

namespace Tests\Feature;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_tenant_user_can_login_and_logout(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->create([
            'org_id' => $company->id,
            'user_type' => 'tenant',
            'role' => 'hr',
            'password' => Hash::make('password'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'user_type' => 'tenant',
        ]);

        $loginResponse->assertOk();
        $loginResponse->assertJsonStructure(['data' => ['user', 'token']]);

        $token = $loginResponse->json('data.token');

        $logoutResponse = $this->withToken($token)->postJson('/api/auth/logout');
        $logoutResponse->assertOk();
    }

    public function test_platform_user_can_login(): void
    {
        $user = User::factory()->create([
            'org_id' => null,
            'user_type' => 'platform',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'user_type' => 'platform',
        ]);

        $response->assertOk();
    }
}
