<?php

namespace App\Domain\Audit\Observers;

class SubscriptionObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'subscription';
    }
}
