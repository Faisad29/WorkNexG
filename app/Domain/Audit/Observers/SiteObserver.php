<?php

namespace App\Domain\Audit\Observers;

class SiteObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'site';
    }
}
