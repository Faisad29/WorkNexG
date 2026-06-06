<?php

namespace Database\Factories;

use App\Domain\Site\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'name' => $this->faker->city().' Site',
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'radius_meters' => 150,
            'is_active' => true,
        ];
    }
}
