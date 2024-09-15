<?php

namespace App\Services\Role\Implementations;

use App\Data\RoleData;
use App\Repositories\Role\Contracts\RoleRepositoryInterface;
use App\Services\Role\Contracts\RoleServiceInterface;
use Illuminate\Support\Collection;

class RoleService implements RoleServiceInterface
{

    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllRoles();
    }

    public function findRoleById(int $id): ?object
    {
        return $this->roleRepository->findById($id);
    }

    public function createRole(RoleData $roleData): object
    {
        return $this->roleRepository->createRole($roleData->toArray());
    }

    public function updateRole(int $id, array $data): bool
    {
        return $this->roleRepository->updateRole($id, $data);
    }

    public function deleteRole(int $id): bool
    {
        return $this->roleRepository->deleteRole($id);
    }

    public function assignPermissionToRole(int $roleId, string $permission): bool
    {
        return $this->roleRepository->assignPermission($roleId, $permission);
    }

    public function deleteMultipleRoles(array $roleIds): bool
    {
        return $this->roleRepository->deleteMultipleRoles($roleIds);
    }
}
