<?php

namespace Database\Factories;

use App\Domain\Billing\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'plan_id' => \App\Domain\Billing\Models\Plan::factory(),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
        ];
    }
}
