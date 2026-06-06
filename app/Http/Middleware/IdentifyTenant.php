<?php

namespace App\Http\Middleware;

use App\Domain\Shared\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        $requestedOrgId = $request->header('X-Organization-Id')
            ?? $request->header('X-Org-Id');

        $orgId = $requestedOrgId;

        if ($orgId === null) {
            $orgId = $user->organizations()
                ->wherePivot('status', 'active')
                ->value('organizations.id');

            if ($orgId === null) {
                abort(403, 'Organization context required.');
            }
        }

        $belongsToOrg = $user->organizations()
            ->where('organizations.id', $orgId)
            ->wherePivot('status', 'active')
            ->exists();

        if (! $belongsToOrg) {
            abort(403, 'Forbidden for organization context.');
        }

        $this->tenantContext->setOrgId((string) $orgId);

        return $next($request);
    }
}
