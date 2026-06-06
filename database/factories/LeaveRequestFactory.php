<?php

namespace Database\Factories;

use App\Domain\Leave\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'employee_id' => \App\Domain\Employee\Models\Employee::factory(),
            'leave_type' => 'annual',
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'reason' => $this->faker->sentence(),
            'status' => 'pending',
            'approved_by' => null,
        ];
    }
}
