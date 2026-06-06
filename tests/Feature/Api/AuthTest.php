<?php

namespace Tests\Feature\Api;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_tenant(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'company_name' => 'Test Company Ltd',
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'company' => ['id', 'name'],
                    'user' => ['id', 'email', 'user_type'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'admin@test.com', 'status' => 'active']);
        $this->assertDatabaseHas('organizations', ['name' => 'Test Company Ltd']);
    }

    public function test_register_validates_required_fields(): void
    {
        $response = $this->postJson('/api/auth/register', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['company_name', 'name', 'email', 'password']);
    }

    public function test_user_can_login(): void
    {
        $company = Company::create(['name' => 'Co', 'country' => 'KSA', 'timezone' => 'Asia/Riyadh', 'is_active' => true]);
        User::create([
            'org_id' => $company->id,
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'user_type' => 'tenant',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'password',
            'user_type' => 'tenant',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
            'user_type' => 'tenant',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $company = Company::create(['name' => 'Co', 'country' => 'KSA', 'timezone' => 'Asia/Riyadh', 'is_active' => true]);
        $user = User::create([
            'org_id' => $company->id,
            'name' => 'Test User',
            'email' => 'me@test.com',
            'password' => bcrypt('password'),
            'user_type' => 'tenant',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/auth/me');
        $response->assertStatus(200)->assertJsonPath('data.email', 'me@test.com');
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }

    public function test_user_can_logout(): void
    {
        $company = Company::create(['name' => 'Co', 'country' => 'KSA', 'timezone' => 'Asia/Riyadh', 'is_active' => true]);
        $user = User::create([
            'org_id' => $company->id,
            'name' => 'Test',
            'email' => 'logout@test.com',
            'password' => bcrypt('password'),
            'user_type' => 'tenant',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/auth/logout');
        $response->assertStatus(200)->assertJsonPath('message', 'logged_out');
    }
}
