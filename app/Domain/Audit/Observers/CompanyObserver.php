<?php

namespace App\Domain\Audit\Observers;

class CompanyObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'organization';
    }
}
