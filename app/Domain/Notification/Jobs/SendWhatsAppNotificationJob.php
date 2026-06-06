<?php

namespace App\Domain\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $recipient,
        public readonly string $message,
    ) {
    }

    public function handle(): void
    {
        // Integrate with WhatsApp Business API provider here.
        // e.g. Twilio, Meta Cloud API
        \Illuminate\Support\Facades\Log::info('WhatsApp notification stub', [
            'recipient' => $this->recipient,
            'message' => substr($this->message, 0, 100),
        ]);
    }

    public function backoff(): array
    {
        return [30, 60, 120];
    }
}
