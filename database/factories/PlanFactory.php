<?php

namespace Database\Factories;

use App\Domain\Billing\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => 'Starter',
            'price' => 49,
            'employee_limit' => 25,
            'features' => ['attendance', 'payroll', 'leave'],
        ];
    }
}
