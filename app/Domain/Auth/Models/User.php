<?php

namespace App\Domain\Auth\Models;

use App\Domain\Organization\Models\Organization;
use App\Domain\Organization\Models\OrganizationUser;
use App\Domain\Shared\TenantContext;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserRole;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    private ?string $legacyOrgId = null;
    private ?string $legacyRole = null;

    protected $appends = [
        'user_type',
        'role',
        'is_active',
    ];

    public function setAttribute($key, $value)
    {
        if ($key === 'org_id') {
            $this->legacyOrgId = $value !== null ? (string) $value : null;

            return $this;
        }

        if ($key === 'role') {
            $this->legacyRole = $value !== null ? (string) $value : null;

            return $this;
        }

        if ($key === 'user_type') {
            return $this;
        }

        if ($key === 'is_active') {
            return parent::setAttribute('status', $value ? 'active' : 'inactive');
        }

        return parent::setAttribute($key, $value);
    }

    protected static function booted(): void
    {
        static::saved(function (self $user): void {
            if ($user->legacyOrgId === null) {
                return;
            }

            $organizationExists = Organization::query()->whereKey($user->legacyOrgId)->exists();

            if (! $organizationExists) {
                return;
            }

            OrganizationUser::query()->updateOrCreate(
                [
                    'org_id' => $user->legacyOrgId,
                    'user_id' => $user->id,
                ],
                [
                    'status' => 'active',
                    'joined_at' => now(),
                    'created_at' => now(),
                ]
            );

            if ($user->legacyRole !== null) {
                $roleMap = [
                    'admin' => 'platform-admin',
                    'hr' => 'hr-manager',
                    'supervisor' => 'supervisor',
                    'employee' => 'employee',
                ];

                $roleSlug = $roleMap[$user->legacyRole] ?? $user->legacyRole;
                $role = Role::query()->whereNull('org_id')->where('slug', $roleSlug)->first();

                if ($role !== null) {
                    UserRole::query()->updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'org_id' => $user->legacyOrgId,
                            'role_id' => $role->id,
                        ],
                        [
                            'assigned_by' => $user->id,
                            'assigned_at' => now(),
                        ]
                    );
                }
            }
        });
    }

    protected static function newFactory(): \Database\Factories\UserFactory
    {
        return \Database\Factories\UserFactory::new();
    }

    protected function userType(): Attribute
    {
        return Attribute::get(fn (): string => 'tenant');
    }

    protected function isActive(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === 'active');
    }

    protected function role(): Attribute
    {
        return Attribute::get(function (): ?string {
            $orgId = app(TenantContext::class)->orgId();

            if ($orgId === null) {
                return null;
            }

            return $this->roles()
                ->wherePivot('org_id', $orgId)
                ->value('roles.slug');
        });
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_users', 'user_id', 'org_id')
            ->withPivot(['id', 'status', 'joined_at', 'created_at']);
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(\Modules\Auth\Models\UserRole::class, 'user_id');
    }

    public function roles(): BelongsToMany
    {
        $orgId = app(TenantContext::class)->orgId();

        $relation = $this->belongsToMany(\Modules\Auth\Models\Role::class, 'user_roles', 'user_id', 'role_id')
            ->withPivot(['id', 'org_id', 'assigned_by', 'assigned_at', 'expires_at']);

        if ($orgId !== null) {
            $relation->wherePivot('org_id', $orgId);
        }

        return $relation;
    }

    public function hasPermission(string $permissionName, ?string $orgId = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $orgContext = $orgId ?? app(TenantContext::class)->orgId();

        if ($orgContext === null) {
            return false;
        }

        $isOrgMember = $this->organizations()
            ->where('organizations.id', $orgContext)
            ->wherePivot('status', 'active')
            ->exists();

        if (! $isOrgMember) {
            return false;
        }

        return $this->roles()
            ->where(function ($query) use ($orgContext): void {
                $query->whereNull('roles.org_id')
                    ->orWhere('roles.org_id', $orgContext);
            })
            ->whereHas('permissions', function ($query) use ($permissionName): void {
                $query->where('permissions.name', $permissionName);
            })
            ->exists();
    }
}
