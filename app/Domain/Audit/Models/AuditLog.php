<?php

namespace App\Domain\Audit\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;
    use HasUuids;

    // NOTE: intentionally does NOT use BelongsToCompany — audit logs are
    // global records and may be created before tenant context is set (e.g. login).
    protected $fillable = [
        'org_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_data',
        'new_data',
        'ip_address',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
}
