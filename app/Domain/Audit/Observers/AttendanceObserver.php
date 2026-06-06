<?php

namespace App\Domain\Audit\Observers;

class AttendanceObserver extends BaseAuditObserver
{
    protected function entityType(): string
    {
        return 'attendance';
    }
}
