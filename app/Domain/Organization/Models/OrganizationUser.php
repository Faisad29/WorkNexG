<?php

namespace App\Domain\Organization\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'organization_users';

    public $timestamps = false;

    protected $fillable = [
        'org_id',
        'user_id',
        'status',
        'joined_at',
        'created_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
