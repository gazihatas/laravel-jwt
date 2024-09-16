<?php

namespace App\Repositories\Role\Implementations;

use App\Repositories\Role\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class PostgreSQLRoleRepository implements RoleRepositoryInterface
{

    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function createRole(array $data): Role
    {
        return Role::create($data);
    }

    public function updateRole(int $id, array $data): bool
    {
        $role = $this->findById($id);
        if ($role) {
            return $role->update($data);
        }
        return false;
    }

    public function deleteRole(int $id): bool
    {
        $role = Role::find($id);

        return $role ? $role->delete() : false;
    }

    public function assignPermission(int $roleId, string $permission): bool
    {
        $role = $this->findById($roleId);
        if ($role) {
            $role->givePermissionTo($permission);
            return true;
        }
        return false;
    }

    public function deleteMultipleRoles(array $roleIds): bool
    {
        return Role::whereIn('id', $roleIds)->delete();
    }

    public function getQueryForRoles(): Builder
    {
        return Role::query();
    }
}
