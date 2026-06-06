<?php

namespace App\Domain\Leave\Services;

use App\Domain\Leave\Models\LeaveRequest;
use App\Domain\Shared\TenantContext;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function requestLeave(array $data): LeaveRequest
    {
        return DB::transaction(function () use ($data): LeaveRequest {
            $data['org_id'] = $this->tenantContext->orgId();
            $data['status'] = $data['status'] ?? 'pending';
            return LeaveRequest::create($data);
        });
    }
}
