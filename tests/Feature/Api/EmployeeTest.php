<?php

namespace Tests\Feature\Api;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Organization\Models\OrganizationUser;
use App\Domain\Shared\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserRole;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Test Co',
            'country' => 'KSA',
            'timezone' => 'Asia/Riyadh',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@testco.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        OrganizationUser::create([
            'org_id' => $this->company->id,
            'user_id' => $this->admin->id,
            'status' => 'active',
            'joined_at' => now(),
            'created_at' => now(),
        ]);

        $platformAdminRole = Role::query()->whereNull('org_id')->where('slug', 'platform-admin')->firstOrFail();

        UserRole::create([
            'user_id' => $this->admin->id,
            'org_id' => $this->company->id,
            'role_id' => $platformAdminRole->id,
            'assigned_by' => $this->admin->id,
            'assigned_at' => now(),
        ]);

        app(TenantContext::class)->setCompanyId($this->company->id);
    }

    public function test_admin_can_list_employees(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->getJson('/api/employees');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_admin_can_create_employee(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->postJson('/api/employees', [
            'employee_code' => 'EMP-001',
            'full_name' => 'Ahmed Test',
            'salary_type' => 'monthly',
            'base_salary' => 5000,
            'status' => 'active',
        ]);

        $response->assertStatus(201)->assertJsonPath('data.employee_code', 'EMP-001');
        $this->assertDatabaseHas('employees', ['employee_code' => 'EMP-001', 'org_id' => $this->company->id]);
    }

    public function test_employee_creation_validates_required_fields(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->postJson('/api/employees', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['employee_code', 'full_name', 'salary_type', 'base_salary', 'status']);
    }

    public function test_duplicate_employee_code_is_rejected(): void
    {
        Sanctum::actingAs($this->admin);

        $this->withHeader('X-Organization-Id', $this->company->id)->postJson('/api/employees', [
            'employee_code' => 'EMP-DUPE',
            'full_name' => 'First',
            'salary_type' => 'monthly',
            'base_salary' => 5000,
            'status' => 'active',
        ]);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->postJson('/api/employees', [
            'employee_code' => 'EMP-DUPE',
            'full_name' => 'Second',
            'salary_type' => 'monthly',
            'base_salary' => 5000,
            'status' => 'active',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['employee_code']);
    }

    public function test_unauthenticated_cannot_access_employees(): void
    {
        $response = $this->getJson('/api/employees');
        $response->assertStatus(401);
    }
}
