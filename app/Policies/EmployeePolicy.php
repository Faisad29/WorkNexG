<?php

namespace App\Policies;

use App\Domain\Auth\Models\User;
use App\Domain\Employee\Models\Employee;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('employee.view_any');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->hasPermission('employee.view', (string) $employee->org_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('employee.create');
    }
}
