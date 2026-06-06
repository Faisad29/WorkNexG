<?php

namespace Database\Factories;

use App\Domain\Organization\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'code' => $this->faker->unique()->bothify('org-####'),
            'status' => 'active',
            'subscription_id' => null,
            'country' => 'KSA',
            'timezone' => 'Asia/Riyadh',
            'settings' => null,
        ];
    }
}
