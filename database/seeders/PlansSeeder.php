<?php

namespace Database\Seeders;

use App\Domain\Billing\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'price' => 99.00,
                'employee_limit' => 25,
                'features' => json_encode(['attendance', 'payroll_basic']),
            ],
            [
                'name' => 'Professional',
                'price' => 299.00,
                'employee_limit' => 100,
                'features' => json_encode(['attendance', 'payroll_full', 'compliance', 'reports']),
            ],
            [
                'name' => 'Enterprise',
                'price' => 799.00,
                'employee_limit' => 500,
                'features' => json_encode(['attendance', 'payroll_full', 'compliance', 'reports', 'api', 'whatsapp']),
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }

        // Seed core permissions
        $permissions = [
            'workforce.access',
            'employees.view',
            'employees.create',
            'employees.update',
            'employees.delete',
            'attendance.view',
            'attendance.checkin',
            'attendance.override',
            'payroll.view',
            'payroll.generate',
            'payroll.approve',
            'compliance.view',
            'compliance.manage',
            'reports.view',
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insertOrIgnore([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'name' => $perm,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
