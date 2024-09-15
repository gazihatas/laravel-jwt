<?php

namespace App\Services\Permission\Implementations;

use App\Repositories\Permission\Contracts\PermissionRepositoryInterface;
use App\Services\Permission\Contracts\PermissionServiceInterface;
use Illuminate\Support\Collection;

class PermissionService implements PermissionServiceInterface
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->getAllPermissions();
    }

    public function findPermissionById(int $id): ?object
    {
        return $this->permissionRepository->findById($id);
    }

    public function createPermission(array $data): object
    {
        // İş mantığı burada yer alabilir
        return $this->permissionRepository->createPermission($data);
    }

    public function updatePermission(int $id, array $data): bool
    {
        return $this->permissionRepository->updatePermission($id, $data);
    }

    public function deletePermission(int $id): bool
    {
        return $this->permissionRepository->deletePermission($id);
    }
}
