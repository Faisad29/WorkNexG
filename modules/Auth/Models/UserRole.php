<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'org_id',
        'role_id',
        'assigned_by',
        'assigned_at',
        'expires_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
