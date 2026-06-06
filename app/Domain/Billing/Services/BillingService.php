<?php

namespace App\Domain\Billing\Services;

use App\Domain\Billing\Models\Subscription;
use App\Domain\Shared\TenantContext;

class BillingService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function subscribe(array $data): Subscription
    {
        $data['org_id'] = $this->tenantContext->orgId();
        return Subscription::create($data + ['status' => 'active']);
    }
}
