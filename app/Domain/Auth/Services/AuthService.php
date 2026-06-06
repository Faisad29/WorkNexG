<?php

namespace App\Domain\Auth\Services;

use App\Domain\Auth\Models\User;
use App\Domain\Organization\Models\Organization;
use App\Domain\Organization\Models\OrganizationUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\UserRole;

class AuthService
{
    public function registerTenant(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            $orgName = $data['organization_name'] ?? $data['company_name'] ?? 'Organization';

            $organization = Organization::create([
                'name' => $orgName,
                'code' => $data['organization_code'] ?? Str::slug($orgName) . '-' . Str::lower(Str::random(6)),
                'status' => 'active',
                'country' => $data['country'] ?? 'KSA',
                'timezone' => $data['timezone'] ?? 'Asia/Riyadh',
                'settings' => null,
            ]);

            $user = User::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($data['password']),
                    'status' => 'active',
                ]
            );

            if (! Hash::check($data['password'], $user->password)) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'status' => 'active',
                ])->save();
            }

            OrganizationUser::query()->firstOrCreate([
                'org_id' => $organization->id,
                'user_id' => $user->id,
            ], [
                'status' => 'active',
                'joined_at' => now(),
                'created_at' => now(),
            ]);

            $legacyRoleMap = [
                'admin' => 'platform-admin',
                'hr' => 'hr-manager',
                'supervisor' => 'supervisor',
                'employee' => 'employee',
            ];

            $requestedRole = $data['role'] ?? 'hr-manager';
            $roleSlug = $legacyRoleMap[$requestedRole] ?? $requestedRole;

            $role = Role::query()->whereNull('org_id')->where('slug', $roleSlug)->first();

            if ($role !== null) {
                UserRole::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'org_id' => $organization->id,
                    'role_id' => $role->id,
                ], [
                    'assigned_by' => $user->id,
                    'assigned_at' => now(),
                ]);
            }

            $token = $user->createToken('organization-registration', ['organization'])->plainTextToken;

            app(\App\Domain\Audit\Services\AuditService::class)->record(
                action: 'register_organization',
                entityType: 'organization',
                entityId: $organization->id,
                oldData: null,
                newData: [
                    'organization' => $organization->toArray(),
                    'user' => $user->toArray(),
                ],
                userId: $user->id,
                ipAddress: request()?->ip(),
            );

            return [
                'organization' => $organization,
                'user' => $user,
                'token' => $token,
            ];
        });
    }

    public function login(string $email, string $password): array
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', $email)
            ->where('status', 'active')
            ->first();

        if ($user === null || ! Hash::check($password, $user->password)) {
            abort(401, 'Invalid credentials.');
        }

        $token = $user->createToken('user-session', ['organization'])->plainTextToken;

        $user->forceFill(['last_login_at' => now()])->save();

        app(\App\Domain\Audit\Services\AuditService::class)->record(
            action: 'login',
            entityType: 'auth',
            entityId: $user->id,
            oldData: null,
            newData: [
                'email' => $user->email,
            ],
            userId: $user->id,
            ipAddress: request()?->ip(),
        );

        $primaryOrgId = $user->organizations()
            ->wherePivot('status', 'active')
            ->value('organizations.id');

        $roleSlug = null;

        if ($primaryOrgId !== null) {
            $roleSlug = $user->roles()
                ->wherePivot('org_id', $primaryOrgId)
                ->value('roles.slug');
        }

        $userData = $user->toArray();
        $userData['primary_org_id'] = $primaryOrgId;
        $userData['role_slug'] = $roleSlug;
        $userData['redirect_to'] = $this->resolveRedirectPath($roleSlug);

        return [
            'user' => $userData,
            'token' => $token,
        ];
    }

    private function resolveRedirectPath(?string $roleSlug): string
    {
        return match ($roleSlug) {
            'employee' => '/my-attendance',
            'supervisor' => '/attendance',
            'hr-manager' => '/employees',
            'accountant' => '/payroll',
            default => '/dashboard',
        };
    }

    public function logout(User $user): void
    {
        $currentToken = $user->currentAccessToken();

        if ($currentToken !== null && method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        }

        app(\App\Domain\Audit\Services\AuditService::class)->record(
            action: 'logout',
            entityType: 'auth',
            entityId: $user->id,
            oldData: [
                'email' => $user->email,
            ],
            newData: null,
            userId: $user->id,
            ipAddress: request()?->ip(),
        );
    }
}
