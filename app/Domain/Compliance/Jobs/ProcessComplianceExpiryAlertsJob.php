<?php

namespace App\Domain\Compliance\Jobs;

use App\Domain\Compliance\Services\ComplianceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessComplianceExpiryAlertsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public function backoff(): array
    {
        return [30, 60, 120];
    }

    public function handle(): void
    {
        app(ComplianceService::class)->processExpiryAlerts(now());
    }
}
