<?php

namespace Database\Seeders;

use App\Data\RoleData;
use App\Enums\PermissionType;
use App\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $roles = [
            RoleData::fromEnum(RoleType::ADMIN,$allPermissions),
            RoleData::fromEnum(RoleType::USER, [
                PermissionType::CREATE_POSTS->value,
                PermissionType::EDIT_POSTS->value
            ]),
            RoleData::fromEnum(RoleType::AUTHOR, [
                PermissionType::CREATE_POSTS->value,
                PermissionType::EDIT_POSTS->value,
                PermissionType::PUBLISH_POSTS->value
            ]),
        ];

        foreach ($roles as $roleData){

            $role = Role::updateOrCreate(
                ['name' => $roleData->name],
                $roleData->toArray()
            );

            $role->syncPermissions($roleData->permissions);
        }
    }
}
