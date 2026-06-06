<?php

namespace Database\Seeders;

use App\Domain\Attendance\Services\AttendanceService;
use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\Organization;
use App\Domain\Organization\Models\OrganizationUser;
use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserRole;

class WorkforceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $tenantContext = app(TenantContext::class);

        $organization = Organization::query()->updateOrCreate(
            ['code' => 'demo-ksa'],
            [
                'name' => 'Demo KSA Organization',
                'status' => 'active',
                'country' => 'KSA',
                'timezone' => 'Asia/Riyadh',
                'settings' => null,
            ]
        );

        $tenantContext->setOrgId((string) $organization->id);

        $adminUser = User::query()->updateOrCreate(
            ['email' => 'admin@worknexg.test'],
            [
                'name' => 'Admin User',
                'phone' => '+966500000000',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $supervisorUser = User::query()->updateOrCreate(
            ['email' => 'supervisor@worknexg.test'],
            [
                'name' => 'Supervisor User',
                'phone' => '+966500000002',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $employeeUser = User::query()->updateOrCreate(
            ['email' => 'employee@worknexg.test'],
            [
                'name' => 'Employee User',
                'phone' => '+966500000001',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        foreach ([$adminUser, $supervisorUser, $employeeUser] as $user) {
            OrganizationUser::query()->updateOrCreate(
                [
                    'org_id' => $organization->id,
                    'user_id' => $user->id,
                ],
                [
                    'status' => 'active',
                    'joined_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $roleMap = [
            $adminUser->id => 'platform-admin',
            $supervisorUser->id => 'supervisor',
            $employeeUser->id => 'employee',
        ];

        foreach ($roleMap as $userId => $roleSlug) {
            $role = Role::query()->whereNull('org_id')->where('slug', $roleSlug)->first();

            if ($role === null) {
                continue;
            }

            UserRole::query()->updateOrCreate(
                [
                    'user_id' => $userId,
                    'org_id' => $organization->id,
                    'role_id' => $role->id,
                ],
                [
                    'assigned_by' => $adminUser->id,
                    'assigned_at' => now(),
                ]
            );
        }

        $site = Site::query()->updateOrCreate(
            [
                'org_id' => $organization->id,
                'name' => 'Riyadh Main Site',
            ],
            [
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'radius_meters' => 150,
                'is_active' => true,
            ]
        );

        $employee = \App\Domain\Employee\Models\Employee::query()->updateOrCreate(
            [
                'org_id' => $organization->id,
                'employee_code' => 'EMP-0001',
            ],
            [
                'site_id' => $site->id,
                'full_name' => 'Ahmed Al-Qahtani',
                'phone' => '+966500000001',
                'email' => 'ahmed@example.test',
                'nationality' => 'Saudi',
                'job_title' => 'Supervisor',
                'role' => 'supervisor',
                'salary_type' => 'monthly',
                'base_salary' => 8000,
                'join_date' => now()->toDateString(),
                'contract_end_date' => now()->addYear()->toDateString(),
                'status' => 'active',
            ]
        );

        app(AttendanceService::class)->checkIn([
            'employee_id' => $employee->id,
            'site_id' => $employee->site_id,
            'attendance_date' => now()->toDateString(),
            'check_in_time' => now(),
            'check_in_latitude' => 24.7136,
            'check_in_longitude' => 46.6753,
            'is_manual_override' => false,
        ]);

        app(\App\Domain\Audit\Services\AuditService::class)->record(
            action: 'seed_demo_data',
            entityType: 'organization',
            entityId: $organization->id,
            oldData: null,
            newData: $organization->toArray(),
            userId: $adminUser->id,
            ipAddress: '127.0.0.1',
        );

        $this->command->info('Demo data seeded.');
        $this->command->info('Logins (all password: password):');
        $this->command->info('  admin@worknexg.test (platform-admin role in demo org)');
        $this->command->info('  supervisor@worknexg.test (supervisor role in demo org)');
        $this->command->info('  employee@worknexg.test (employee role in demo org)');
    }
}
