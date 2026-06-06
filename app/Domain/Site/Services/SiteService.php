<?php

namespace App\Domain\Site\Services;

use App\Domain\Shared\TenantContext;
use App\Domain\Site\Models\Site;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SiteService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function list(): LengthAwarePaginator
    {
        return Site::query()->where('is_active', true)->latest()->paginate(25);
    }

    public function create(array $data): Site
    {
        $data['org_id'] = $this->tenantContext->orgId();
        return Site::create($data);
    }
}
