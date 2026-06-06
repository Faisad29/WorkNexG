<?php

namespace App\Domain\Compliance\Services;

use App\Domain\Compliance\Events\DocumentExpiring;
use App\Domain\Compliance\Models\Document;
use Carbon\CarbonInterface;

class ComplianceService
{
    public function expiringDocuments(CarbonInterface $date): \Illuminate\Database\Eloquent\Collection
    {
        return Document::query()
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', $date)
            ->get();
    }

    public function processExpiryAlerts(CarbonInterface $today): void
    {
        foreach ([30, 15, 7] as $days) {
            $targetDate = $today->copy()->addDays($days)->toDateString();

            Document::query()
                ->whereDate('expiry_date', $targetDate)
                ->get()
                ->each(function (Document $document) use ($days): void {
                    DocumentExpiring::dispatch($document->id, $document->org_id, $days);
                });
        }
    }
}
