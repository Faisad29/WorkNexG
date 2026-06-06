<?php

namespace App\Domain\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public function __construct(
        public string $recipient,
        public string $message,
    ) {
    }

    public function handle(): void
    {
        // Integrate with a mail provider here.
    }

    public function backoff(): array
    {
        return [10, 30, 60, 120];
    }

    public function failed(\Throwable $exception): void
    {
        report($exception);
    }
}
