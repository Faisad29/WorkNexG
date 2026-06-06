<?php

namespace Tests\Feature\Api;

use App\Domain\Auth\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Employee\Models\Employee;
use App\Domain\Organization\Models\OrganizationUser;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserRole;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Company $company;
    private Site $site;
    private Employee $employee;

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

        $this->site = Site::create([
            'org_id' => $this->company->id,
            'name' => 'Test Site',
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'radius_meters' => 10000, // Large radius for tests
            'is_active' => true,
        ]);

        $this->employee = Employee::create([
            'org_id' => $this->company->id,
            'site_id' => $this->site->id,
            'employee_code' => 'EMP-001',
            'full_name' => 'Test Employee',
            'salary_type' => 'monthly',
            'base_salary' => 5000,
            'status' => 'active',
        ]);
    }

    public function test_can_check_in_within_radius(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)
            ->postJson('/api/attendance/check-in', [
            'employee_id' => $this->employee->id,
            'site_id' => $this->site->id,
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now()->toDateTimeString(),
            'check_in_latitude' => 24.7136,
            'check_in_longitude' => 46.6753,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.employee_id', $this->employee->id);

        $this->assertDatabaseHas('attendance_records', [
            'employee_id' => $this->employee->id,
            'org_id' => $this->company->id,
        ]);
    }

    public function test_check_in_fails_outside_radius(): void
    {
        Sanctum::actingAs($this->admin);

        // Use small radius site
        $smallSite = Site::create([
            'org_id' => $this->company->id,
            'name' => 'Small Site',
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'radius_meters' => 1, // 1 meter radius
            'is_active' => true,
        ]);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)
            ->postJson('/api/attendance/check-in', [
            'employee_id' => $this->employee->id,
            'site_id' => $smallSite->id,
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now()->toDateTimeString(),
            'check_in_latitude' => 25.0, // Far away
            'check_in_longitude' => 47.0,
        ]);

        $response->assertStatus(422);
    }

    public function test_can_list_attendance(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->getJson('/api/attendance');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_check_in_validates_required_fields(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->withHeader('X-Organization-Id', $this->company->id)->postJson('/api/attendance/check-in', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['employee_id', 'site_id', 'attendance_date', 'check_in_time']);
    }
}
