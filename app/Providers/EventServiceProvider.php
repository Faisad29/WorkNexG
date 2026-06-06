<?php

namespace App\Providers;

use App\Domain\Attendance\Events\AttendanceMarked;
use App\Domain\Attendance\Listeners\RecomputeAttendanceMetrics;
use App\Domain\Compliance\Events\DocumentExpiring;
use App\Domain\Compliance\Listeners\SendDocumentExpiryNotification;
use App\Domain\Notification\Events\NotificationSent;
use App\Domain\Notification\Listeners\PersistNotificationAudit;
use App\Domain\Payroll\Events\PayrollGenerated;
use App\Domain\Payroll\Listeners\QueuePayrollNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AttendanceMarked::class => [
            RecomputeAttendanceMetrics::class,
        ],
        PayrollGenerated::class => [
            QueuePayrollNotification::class,
        ],
        DocumentExpiring::class => [
            SendDocumentExpiryNotification::class,
        ],
        NotificationSent::class => [
            PersistNotificationAudit::class,
        ],
    ];
}
