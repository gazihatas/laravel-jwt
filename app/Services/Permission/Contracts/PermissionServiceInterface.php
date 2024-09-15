<?php

namespace App\Services\Permission\Contracts;

use Illuminate\Support\Collection;

interface PermissionServiceInterface
{
    public function getAllPermissions(): Collection;

    public function findPermissionById(int $id): ?object;

    public function createPermission(array $data): object;

    public function updatePermission(int $id, array $data): bool;

    public function deletePermission(int $id): bool;
}
