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

/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     description="Role model",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Administrator"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
     * @OA\Get(
     *     path="/api/v1/roles",
     *     summary="Get all roles",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of roles per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Role")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/v1/roles",
     *     summary="Create a new role",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Administrator")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/v1/roles/{id}",
     *     summary="Get a role by ID",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/v1/roles/{id}",
     *     summary="Update a role",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Moderator")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/v1/roles/{id}",
     *     summary="Delete a role",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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

    /**
     * @OA\Post(
     *     path="/api/v1/roles/delete-multiple",
     *     summary="Delete multiple roles",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role_ids"},
     *             @OA\Property(property="role_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Roles deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No role ID provided"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
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
     * @OA\Post(
     *     path="/api/v1/roles/{id}/permissions",
     *     summary="Assign permissions to a role",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Role ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permissions"},
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"), example={"create", "edit"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissions assigned successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
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
