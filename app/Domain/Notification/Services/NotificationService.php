<?php

namespace App\Domain\Notification\Services;

use App\Domain\Notification\Events\NotificationSent;
use App\Domain\Notification\Jobs\SendEmailNotificationJob;
use App\Domain\Notification\Jobs\SendSmsNotificationJob;
use App\Domain\Notification\Jobs\SendWhatsAppNotificationJob;

class NotificationService
{
    public function send(string $channel, string $recipient, string $message): void
    {
        match ($channel) {
            'whatsapp' => SendWhatsAppNotificationJob::dispatch($recipient, $message),
            'sms' => SendSmsNotificationJob::dispatch($recipient, $message),
            'email' => SendEmailNotificationJob::dispatch($recipient, $message),
            default => throw new \InvalidArgumentException('Unsupported notification channel.'),
        };

        NotificationSent::dispatch($channel, $recipient, $message);
    }
}

