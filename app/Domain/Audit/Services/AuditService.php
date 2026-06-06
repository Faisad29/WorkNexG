<?php

namespace App\Domain\Audit\Services;

use App\Domain\Audit\Models\AuditLog;
use App\Domain\Shared\TenantContext;

class AuditService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function record(
        string $action,
        string $entityType,
        string $entityId,
        ?array $oldData,
        ?array $newData,
        ?string $userId = null,
        ?string $ipAddress = null,
    ): AuditLog {
        return AuditLog::create([
            'org_id' => $this->tenantContext->orgId(),
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => $ipAddress,
        ]);
    }
}
