<?php

namespace App\Providers;

use App\Domain\Employee\Models\Employee;
use App\Domain\Leave\Models\LeaveRequest;
use App\Domain\Payroll\Models\Payroll;
use App\Policies\EmployeePolicy;
use App\Policies\LeavePolicy;
use App\Policies\PayrollPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Payroll::class => PayrollPolicy::class,
        LeaveRequest::class => LeavePolicy::class,
    ];
}
