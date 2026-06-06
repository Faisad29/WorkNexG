<?php

namespace App\Domain\Compliance\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentExpiring
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $documentId,
        public readonly string $companyId,
        public readonly int $daysUntilExpiry,
    ) {
    }
}
