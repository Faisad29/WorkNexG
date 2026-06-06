<?php

namespace App\Domain\Audit\Observers;

class DocumentObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'document';
    }
}
