<?php

namespace App\Domain\Audit\Observers;

use App\Domain\Audit\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

abstract class BaseAuditObserver
{
    public function __construct(protected readonly AuditService $auditService)
    {
    }

    abstract protected function entityType(): string;

    public function created(Model $model): void
    {
        $this->safeRecord('created', $model, null, $model->toArray());
    }

    public function updated(Model $model): void
    {
        $this->safeRecord('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->safeRecord('deleted', $model, $model->toArray(), null);
    }

    private function safeRecord(string $action, Model $model, ?array $old, ?array $new): void
    {
        try {
            $this->auditService->record(
                action: $action,
                entityType: $this->entityType(),
                entityId: (string) $model->getKey(),
                oldData: $old,
                newData: $new,
            );
        } catch (\Throwable) {
            // Never let audit logging break the main flow
        }
    }
}
