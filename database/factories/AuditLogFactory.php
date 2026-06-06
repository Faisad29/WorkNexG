<?php

namespace Database\Factories;

use App\Domain\Audit\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'org_id' => \App\Domain\Company\Models\Company::factory(),
            'user_id' => null,
            'action' => 'create',
            'entity_type' => 'employee',
            'entity_id' => (string) \Illuminate\Support\Str::uuid(),
            'old_data' => null,
            'new_data' => ['source' => 'factory'],
            'ip_address' => '127.0.0.1',
        ];
    }
}
