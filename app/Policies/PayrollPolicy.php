<?php

namespace App\Policies;

use App\Domain\Auth\Models\User;
use App\Domain\Payroll\Models\Payroll;

class PayrollPolicy
{
    public function view(User $user, Payroll $payroll): bool
    {
        return $user->hasPermission('payroll.view', (string) $payroll->org_id);
    }

    public function approve(User $user): bool
    {
        return $user->hasPermission('payroll.approve');
    }
}
