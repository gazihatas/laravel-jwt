<?php

namespace App\Http\Controllers\V1\Authorization;

use App\Data\RoleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Authorization\CreateRoleRequest;
use App\Http\Requests\Api\Role\AssignPermissionRequest;
use App\Http\Requests\Api\Role\DeleteRoleRequest;
use App\Http\Requests\Api\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Pagination\PaginationService;
use App\Services\Role\Contracts\RoleServiceInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ApiResponseTrait;

    protected $roleService;
    protected  $paginationService;

    public function __construct(RoleServiceInterface $roleService, PaginationService $paginationService)
    {
        $this->roleService = $roleService;
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 2);
            $page = $request->input('page', 1);

            $paginationData = $this->roleService->getPaginatedRoles($perPage, $page);

            return $this->successResponse($paginationData->toDataArray('roles'), 'Roles retrieved successfully',$paginationData->toMetaArray());

        } catch (\Exception $e) {
            Log::error('Error retrieving roles: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve roles');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        try {
            $roleData = RoleData::fromCreateRequest($request);
            $role = $this->roleService->createRole($roleData);
            return $this->successResponse($role, 'Role created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return $this->errorResponse('Failed to create role');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $role = $this->roleService->findRoleById($id);
            return $this->successResponse($role, 'Role retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error retrieving role: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve role');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, int $id)
    {
        try {
            $roleData = RoleData::fromUpdateRequest($request);
            $updated = $this->roleService->updateRole($id, $roleData->toArray());
            return $updated ?
                $this->successResponse(null, 'Role updated successfully') :
                $this->errorResponse('Failed to update role');
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return $this->errorResponse('Failed to update role');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id = null)
    {
        try {

            $deleted = $this->roleService->deleteRole($id);
            return $deleted ?
                $this->successResponse(null, 'Role deleted successfully') :
                $this->errorResponse('Failed to delete role');

        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete role');
        }
    }

    public function deleteMultiple(DeleteRoleRequest $request)
    {
        try {
            $roleData = RoleData::fromDeleteRequest($request);

            if (!empty($roleData->roleIds)) {
                $deleted = $this->roleService->deleteMultipleRoles($roleData->roleIds);
                return $deleted
                    ? $this->successResponse(null, 'Roles deleted successfully')
                    : $this->errorResponse('Failed to delete roles');
            }

            return $this->errorResponse('No role id or role_ids provided', 400);

        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete role');
        }
    }

    /**
     * Role izin atama işlemi.
     */
    public function assignPermissionToRole(AssignPermissionRequest $request, int $roleId)
    {
        try {
            $role = Role::findOrFail($roleId);

            $permissions = $request->input('permissions');

            $role->syncPermissions($permissions);

            return $this->successResponse(null, 'Permissions assigned to role successfully');
        } catch (\Exception $e) {
            Log::error('Error assigning permissions to role: ' . $e->getMessage());
            return $this->errorResponse('Failed to assign permissions to role');
        }
    }
}
