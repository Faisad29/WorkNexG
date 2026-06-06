<?php

namespace App\Domain\Audit\Observers;

class PayrollItemObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'payrollitem';
    }
}
