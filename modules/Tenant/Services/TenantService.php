<?php

namespace Modules\Tenant\Services;

use App\Domain\Organization\Models\Organization;

class TenantService
{
    public function createOrganization(array $data): Organization
    {
        return Organization::create($data);
    }
}
