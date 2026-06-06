<?php

namespace App\Domain\Shared\Contracts;

interface TenantScoped
{
    public function orgId(): string;
}
