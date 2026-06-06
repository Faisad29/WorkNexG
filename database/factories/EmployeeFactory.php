<?php

namespace Database\Factories;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'site_id' => \App\Domain\Site\Models\Site::factory(),
            'employee_code' => 'EMP-'.$this->faker->unique()->numberBetween(1000, 9999),
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'nationality' => $this->faker->country(),
            'job_title' => $this->faker->jobTitle(),
            'role' => 'employee',
            'salary_type' => 'monthly',
            'base_salary' => 6000,
            'join_date' => now()->subMonth()->toDateString(),
            'contract_end_date' => now()->addYear()->toDateString(),
            'status' => 'active',
        ];
    }
}
