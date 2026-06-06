<?php

namespace App\Domain\Payroll\Listeners;

use App\Domain\Notification\Jobs\SendEmailNotificationJob;
use App\Domain\Payroll\Events\PayrollGenerated;
use App\Domain\Payroll\Models\Payroll;
use App\Domain\Auth\Models\User;

class QueuePayrollNotification
{
    public function handle(PayrollGenerated $event): void
    {
        $payroll = Payroll::query()->find($event->payrollId);
        if ($payroll === null) {
            return;
        }

        // Notify admin users of the company
        User::query()
            ->where('org_id', $event->companyId)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->get()
            ->each(function (User $user) use ($payroll): void {
                if (empty($user->email)) {
                    return;
                }
                $message = sprintf(
                    'Payroll for %s %d has been generated. Total: SAR %s for %d employees. Please review and approve.',
                    date('F', mktime(0, 0, 0, $payroll->month, 1)),
                    $payroll->year,
                    number_format((float) $payroll->total_amount, 2),
                    $payroll->total_employees,
                );
                SendEmailNotificationJob::dispatch($user->email, $message);
            });
    }
}
