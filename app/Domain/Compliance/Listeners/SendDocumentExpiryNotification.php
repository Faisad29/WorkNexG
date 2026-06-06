<?php

namespace App\Domain\Compliance\Listeners;

use App\Domain\Compliance\Events\DocumentExpiring;
use App\Domain\Compliance\Models\Document;
use App\Domain\Notification\Jobs\SendEmailNotificationJob;

class SendDocumentExpiryNotification
{
    public function handle(DocumentExpiring $event): void
    {
        $document = Document::query()->with('employee')->find($event->documentId);
        if ($document === null) {
            return;
        }

        $employee = $document->employee;
        if ($employee === null || empty($employee->email)) {
            return;
        }

        $message = sprintf(
            'Dear %s, your %s document (No: %s) expires in %d day(s) on %s. Please renew it promptly.',
            $employee->full_name,
            strtoupper($document->type),
            $document->document_number ?? 'N/A',
            $event->daysUntilExpiry,
            $document->expiry_date?->format('d M Y'),
        );

        SendEmailNotificationJob::dispatch($employee->email, $message);
    }
}
