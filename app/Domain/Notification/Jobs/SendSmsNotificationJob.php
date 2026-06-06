<?php

namespace App\Domain\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsNotificationJob implements ShouldQueue
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
        // Integrate with SMS provider here (Twilio, Unifonic, Taqnyat, etc.)
        \Illuminate\Support\Facades\Log::info('SMS notification stub', [
            'recipient' => $this->recipient,
        ]);
    }

    public function backoff(): array
    {
        return [30, 60, 120];
    }
}
