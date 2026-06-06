<?php

namespace Modules\Auth\Http\Middleware;

use App\Domain\Shared\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        $orgId = $this->tenantContext->orgId();

        if ($orgId === null) {
            abort(403, 'Organization context missing.');
        }

        $hasRole = $user->roles()
            ->wherePivot('org_id', $orgId)
            ->whereIn('roles.slug', $roles)
            ->exists();

        if (! $hasRole) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
