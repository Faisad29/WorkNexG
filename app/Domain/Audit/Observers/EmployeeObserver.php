<?php

namespace App\Domain\Audit\Observers;

class EmployeeObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'employee';
    }
}
