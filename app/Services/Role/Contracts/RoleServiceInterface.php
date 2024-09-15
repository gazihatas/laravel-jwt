<?php

namespace App\Services\Role\Contracts;

use App\Data\RoleData;
use Illuminate\Support\Collection;

interface RoleServiceInterface
{
    public function getAllRoles(): Collection;

    public function findRoleById(int $id): ?object;

    public function createRole(RoleData $roleData): object;

    public function updateRole(int $id, array $data): bool;

    public function deleteRole(int $id): bool;

    public function assignPermissionToRole(int $roleId, string $permission): bool;

    public function deleteMultipleRoles(array $roleIds): bool;
}
