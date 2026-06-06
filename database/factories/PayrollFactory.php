<?php

namespace Database\Factories;

use App\Domain\Payroll\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollFactory extends Factory
{
    protected $model = Payroll::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'month' => (int) now()->month,
            'year' => (int) now()->year,
            'total_employees' => 1,
            'total_amount' => 6000,
            'status' => 'generated',
        ];
    }
}
