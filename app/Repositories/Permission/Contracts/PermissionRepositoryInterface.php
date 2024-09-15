<?php

namespace App\Repositories\Permission\Contracts;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    public function getAllPermissions(): Collection;

    public function findById(int $id): ?Permission;

    public function createPermission(array $data): Permission;

    public function updatePermission(int $id,array $data):bool;

    public function deletePermission(int $id):bool;
}
