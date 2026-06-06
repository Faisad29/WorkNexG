<?php

namespace App\Domain\Compliance\Services;

use App\Domain\Compliance\Models\Document;
use App\Domain\Shared\TenantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentService
{
    public function __construct(private readonly TenantContext $tenantContext)
    {
    }

    public function list(): LengthAwarePaginator
    {
        return Document::query()->with('employee')->latest()->paginate(25);
    }

    public function create(array $data): Document
    {
        $data['org_id'] = $this->tenantContext->orgId();
        return Document::create($data);
    }
}
