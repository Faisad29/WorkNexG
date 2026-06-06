<?php

namespace App\Providers;

use App\Domain\Audit\Observers\AttendanceObserver;
use App\Domain\Audit\Observers\CompanyObserver;
use App\Domain\Audit\Observers\DocumentObserver;
use App\Domain\Audit\Observers\EmployeeObserver;
use App\Domain\Audit\Observers\LeaveObserver;
use App\Domain\Audit\Observers\PayrollItemObserver;
use App\Domain\Audit\Observers\PayrollObserver;
use App\Domain\Audit\Observers\SiteObserver;
use App\Domain\Audit\Observers\SubscriptionObserver;
use App\Domain\Audit\Observers\UserObserver;
use App\Domain\Attendance\Models\AttendanceRecord;
use App\Domain\Billing\Models\Subscription;
use App\Domain\Organization\Models\Organization;
use App\Domain\Compliance\Models\Document;
use App\Domain\Auth\Models\User;
use App\Domain\Employee\Models\Employee;
use App\Domain\Leave\Models\LeaveRequest;
use App\Domain\Payroll\Models\Payroll;
use App\Domain\Payroll\Models\PayrollItem;
use App\Domain\Shared\TenantContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Site\Models\Site;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, fn () => new TenantContext());
    }

    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(static fn (string $modelName): string => 'Database\\Factories\\' . class_basename($modelName) . 'Factory');

        Organization::observe(CompanyObserver::class);
        User::observe(UserObserver::class);
        Site::observe(SiteObserver::class);
        Employee::observe(EmployeeObserver::class);
        AttendanceRecord::observe(AttendanceObserver::class);
        Payroll::observe(PayrollObserver::class);
        PayrollItem::observe(PayrollItemObserver::class);
        LeaveRequest::observe(LeaveObserver::class);
        Document::observe(DocumentObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}

