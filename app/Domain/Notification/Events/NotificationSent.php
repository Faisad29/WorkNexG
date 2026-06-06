<?php

namespace App\Domain\Notification\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $channel,
        public readonly string $recipient,
        public readonly string $message,
    ) {
    }
}
