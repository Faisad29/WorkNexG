<?php

namespace Database\Factories;

use App\Domain\Compliance\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'employee_id' => \App\Domain\Employee\Models\Employee::factory(),
            'type' => 'iqama',
            'document_number' => (string) $this->faker->unique()->numberBetween(100000, 999999),
            'file_url' => 'https://example.test/doc.pdf',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addMonths(6)->toDateString(),
            'metadata' => ['source' => 'factory'],
        ];
    }
}
