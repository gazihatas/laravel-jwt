<?php

namespace App\Services\Role\Implementations;

use App\Data\PaginationData;
use App\Data\RoleData;
use App\Repositories\Role\Contracts\RoleRepositoryInterface;
use App\Services\Pagination\PaginationService;
use App\Services\Role\Contracts\RoleServiceInterface;
use Illuminate\Support\Collection;

class RoleService implements RoleServiceInterface
{

    protected $roleRepository;
    protected $paginationService;

    public function __construct(RoleRepositoryInterface $roleRepository,PaginationService $paginationService)
    {
        $this->roleRepository = $roleRepository;
        $this->paginationService = $paginationService;
    }

    public function getPaginatedRoles(int $perPage, int $page): PaginationData
    {
        $query = $this->roleRepository->getQueryForRoles();

        return $this->paginationService->paginate($query, $perPage, $page);
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
