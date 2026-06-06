<?php

namespace Modules\Auth\Http\Middleware;

use App\Domain\Shared\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        $orgId = $this->tenantContext->orgId();

        if ($orgId === null) {
            abort(403, 'Organization context missing.');
        }

        if (! method_exists($user, 'hasPermission') || ! $user->hasPermission($permission, $orgId)) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
