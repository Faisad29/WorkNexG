<?php

namespace App\Domain\Audit\Observers;

class UserObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'user';
    }
}
