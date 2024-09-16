<?php

namespace App\Repositories\Role\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function getAllRoles(): Collection;

    public function findById(int $id): ?Role;

    public function createRole(array $data): Role;

    public function updateRole(int $id,array $data): bool;

    public function deleteRole(int $id): bool;

    public function assignPermission(int $roleId, string $permission): bool;

    public function deleteMultipleRoles(array $roleIds): bool;

    public function getQueryForRoles():Builder;
}
