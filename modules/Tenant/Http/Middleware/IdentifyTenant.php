<?php

namespace Modules\Tenant\Http\Middleware;

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
            $this->tenantContext->setOrgId(null);

            return $next($request);
        }

        $requestedOrgId = $request->header('X-Organization-Id')
            ?? $request->header('X-Org-Id');

        $orgId = $requestedOrgId;

        if ($orgId === null) {
            $orgId = $user->organizations()
                ->wherePivot('status', 'active')
                ->value('organizations.id');
        }

        if ($orgId !== null) {
            $allowed = $user->organizations()
                ->where('organizations.id', $orgId)
                ->wherePivot('status', 'active')
                ->exists();

            if (! $allowed) {
                abort(403, 'Forbidden for organization context.');
            }

            $this->tenantContext->setOrgId((string) $orgId);
        } else {
            $this->tenantContext->setOrgId(null);
        }

        return $next($request);
    }
}
