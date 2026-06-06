<?php

use App\Domain\Compliance\Jobs\ProcessComplianceExpiryAlertsJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('app:ping', function () {
    $this->info('pong');
})->purpose('Health check command');

Schedule::job(new ProcessComplianceExpiryAlertsJob())->dailyAt('08:00');
