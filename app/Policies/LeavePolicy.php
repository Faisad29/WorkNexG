<?php

namespace App\Policies;

use App\Domain\Auth\Models\User;
use App\Domain\Leave\Models\LeaveRequest;

class LeavePolicy
{
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('leave.view', (string) $leaveRequest->org_id);
    }

    public function approve(User $user): bool
    {
        return $user->hasPermission('leave.approve');
    }
}
