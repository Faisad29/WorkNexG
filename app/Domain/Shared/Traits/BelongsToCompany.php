<?php

namespace App\Domain\Shared\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope('organization', function (Builder $builder): void {
            $orgId = app(\App\Domain\Shared\TenantContext::class)->orgId();

            if ($orgId === null) {
                if (! app()->runningInConsole()) {
                    abort(403, 'Organization context missing.');
                }

                return;
            }

            $builder->where($builder->getModel()->getTable() . '.org_id', $orgId);
        });

        static::creating(function (Model $model): void {
            $orgId = app(\App\Domain\Shared\TenantContext::class)->orgId();

            if ($orgId === null && ! app()->runningInConsole()) {
                abort(403, 'Organization context missing.');
            }

            if ($orgId !== null && empty($model->org_id)) {
                $model->org_id = $orgId;
            }
        });
    }
}
