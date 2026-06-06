<?php

namespace App\Domain\Organization\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'code',
        'status',
        'subscription_id',
        'timezone',
        'country',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $organization): void {
            if (! empty($organization->code)) {
                return;
            }

            $base = Str::slug($organization->name ?: 'organization');

            do {
                $candidate = $base . '-' . Str::lower(Str::random(6));
            } while (self::query()->where('code', $candidate)->exists());

            $organization->code = $candidate;
        });
    }

    protected static function newFactory(): \Database\Factories\OrganizationFactory
    {
        return \Database\Factories\OrganizationFactory::new();
    }

    public function employees(): HasMany
    {
        return $this->hasMany(\App\Domain\Employee\Models\Employee::class, 'org_id');
    }

    public function sites(): HasMany
    {
        return $this->hasMany(\App\Domain\Site\Models\Site::class, 'org_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Domain\Auth\Models\User::class, 'organization_users', 'org_id', 'user_id')
            ->withPivot(['id', 'status', 'joined_at', 'created_at']);
    }
}
