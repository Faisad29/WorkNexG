<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissionNames = [
            'workforce.access',
            'employee.view',
            'employee.view_any',
            'employee.create',
            'employee.update',
            'employee.delete',
            'attendance.view',
            'attendance.check_in',
            'attendance.check_out',
            'attendance.override.request',
            'attendance.override.approve',
            'payroll.view',
            'payroll.generate',
            'payroll.approve',
            'payroll.lock',
            'payroll.pay',
            'compliance.view',
            'compliance.manage',
            'notification.send',
            'audit.view',
            'settings.manage',
        ];

        foreach ($permissionNames as $permissionName) {
            Permission::query()->firstOrCreate(['name' => $permissionName]);
        }

        $permissionIds = Permission::query()->pluck('id', 'name');

        $roles = [
            [
                'name' => 'Platform Admin',
                'slug' => 'platform-admin',
                'description' => 'Full platform and organization access.',
                'is_system' => true,
                'permissions' => $permissionNames,
            ],
            [
                'name' => 'Support Engineer',
                'slug' => 'support-engineer',
                'description' => 'Support operations and diagnostics.',
                'is_system' => true,
                'permissions' => ['workforce.access', 'audit.view'],
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Employee self-service access.',
                'is_system' => true,
                'permissions' => ['workforce.access', 'attendance.view', 'payroll.view', 'compliance.view'],
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'description' => 'Supervisor access for attendance and leave approvals.',
                'is_system' => true,
                'permissions' => [
                    'workforce.access',
                    'employee.view',
                    'attendance.view',
                    'attendance.check_in',
                    'attendance.check_out',
                    'attendance.override.approve',
                    'payroll.view',
                    'compliance.view',
                    'notification.send',
                ],
            ],
            [
                'name' => 'HR Manager',
                'slug' => 'hr-manager',
                'description' => 'HR and workforce administration.',
                'is_system' => true,
                'permissions' => [
                    'workforce.access',
                    'employee.view',
                    'employee.view_any',
                    'employee.create',
                    'employee.update',
                    'attendance.view',
                    'attendance.override.request',
                    'payroll.view',
                    'payroll.generate',
                    'compliance.manage',
                    'notification.send',
                    'settings.manage',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::query()->updateOrCreate(
                [
                    'org_id' => null,
                    'slug' => $roleData['slug'],
                ],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                    'is_system' => $roleData['is_system'],
                ]
            );

            $role->permissions()->sync(
                collect($roleData['permissions'])
                    ->map(fn (string $name) => $permissionIds->get($name))
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }
}
