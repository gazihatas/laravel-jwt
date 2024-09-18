<?php

namespace App\Data;

use App\Enums\RoleType;
use App\Http\Requests\Api\Authorization\CreateRoleRequest;
use App\Http\Requests\Api\Role\DeleteRoleRequest;
use App\Http\Requests\Api\Role\UpdateRoleRequest;

class RoleData
{
    public function __construct(
        public int|string|null $id,
        public ?string $name,
        public array $roleIds = []
    ){}

    public static function fromCreateRequest(CreateRoleRequest $request) : self
    {
        return new self(
            null,
            $request->input('name')
        );
    }

    public static function fromUpdateRequest(UpdateRoleRequest $request): self
    {
        return new self(
            $request->input('id'),
            $request->input('name')
        );
    }

    public static function fromDeleteRequest(DeleteRoleRequest $request): self
    {
        return new self(
            null,
            null,
            $request->input('role_ids', [])
        );
    }

    public static function fromEnum(RoleType $roleType, array $permissions = []):self
    {
        return new self(
            null,
            $roleType->value,
            $permissions
        );
    }

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
           'role_ids' => $this->roleIds,
        ];
    }
}
