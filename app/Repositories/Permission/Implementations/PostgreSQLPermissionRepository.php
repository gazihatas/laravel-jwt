<?php

namespace App\Repositories\Permission\Implementations;

use App\Repositories\Permission\Contracts\PermissionRepositoryInterface;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PostgreSQLPermissionRepository implements PermissionRepositoryInterface
{

    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function updatePermission(int $id, array $data): bool
    {
        $permission = $this->findById($id);
        if ($permission) {
            return $permission->update($data);
        }
        return false;
    }

    public function deletePermission(int $id): bool
    {
        $permission = $this->findById($id);
        if ($permission) {
            return $permission->delete();
        }
        return false;
    }
}
