<?php

namespace App\Domain\Audit\Observers;

class LeaveObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'leave';
    }
}
