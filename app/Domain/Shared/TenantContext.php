<?php

namespace App\Domain\Shared;

final class TenantContext
{
    private ?string $orgId = null;

    public function setOrgId(?string $orgId): void
    {
        $this->orgId = $orgId;
    }

    public function orgId(): ?string
    {
        return $this->orgId;
    }

    public function setCompanyId(?string $companyId): void
    {
        $this->setOrgId($companyId);
    }

    public function companyId(): ?string
    {
        return $this->orgId;
    }
}
