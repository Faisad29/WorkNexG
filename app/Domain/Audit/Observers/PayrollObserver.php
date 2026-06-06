<?php

namespace App\Domain\Audit\Observers;

class PayrollObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'payroll';
    }
}
