<?php

namespace App\Domain\Employee\Services;

use App\Domain\Employee\Models\Employee;
use App\Domain\Shared\TenantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function list(): LengthAwarePaginator
    {
        return Employee::query()
            ->with('site')
            ->latest()
            ->paginate(25);
    }

    public function create(array $data): Employee
    {
        return DB::transaction(function () use ($data): Employee {
            $data['org_id'] = $this->tenantContext->orgId();
            return Employee::create($data);
        });
    }

    public function update(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data): Employee {
            $employee->update($data);
            return $employee->fresh();
        });
    }
}
