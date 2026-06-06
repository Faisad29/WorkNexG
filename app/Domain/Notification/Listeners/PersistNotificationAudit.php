<?php

namespace App\Domain\Notification\Listeners;

use App\Domain\Audit\Services\AuditService;
use App\Domain\Notification\Events\NotificationSent;

class PersistNotificationAudit
{
    public function __construct(private readonly AuditService $auditService)
    {
    }

    public function handle(NotificationSent $event): void
    {
        $this->auditService->record(
            action: 'notification_sent',
            entityType: 'notification',
            entityId: \Illuminate\Support\Str::uuid()->toString(),
            oldData: null,
            newData: [
                'channel' => $event->channel,
                'recipient' => $event->recipient,
                'message_length' => strlen($event->message),
            ],
        );
    }
}
