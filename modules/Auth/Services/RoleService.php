<?php

namespace Modules\Auth\Services;

use Modules\Auth\Models\Role;

class RoleService
{
    public function createRole(array $data): Role
    {
        return Role::create($data);
    }
}
