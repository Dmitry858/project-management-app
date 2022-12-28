<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Role;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function getUserRoles(int $userId): array
    {
        if (Cache::has('user_'.$userId.'_roles'))
        {
            $roles = Cache::get('user_'.$userId.'_roles');
        }
        else
        {
            $user = User::find($userId);
            $roles = $user ? $user->roles()->get()->toArray() : [];
            Cache::put('user_'.$userId.'_roles', $roles, 180);
        }

        return $roles;
    }

    public function find(int $id)
    {
        return Role::find($id);
    }

    public function search(array $filter = [])
    {
        if (empty($filter) && Cache::has('all_roles'))
        {
            $roles = Cache::get('all_roles');
        }
        else
        {
            $roles = Role::where($filter)->get();
            if (empty($filter))
            {
                Cache::put('all_roles', $roles, 14400);
            }
        }

        return $roles;
    }

    public function createFromArray(array $data)
    {
        return Role::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $role = $this->find($id);

        return $role ? $role->update($data) : false;
    }

    public function syncingPermissions(int $roleId, array $permissionIds = [])
    {
        $role = Role::find($roleId);

        return $role ? $role->permissions()->sync($permissionIds) : false;
    }

    public function attachPermissions(int $roleId, array $permissionIds = [])
    {
        $role = Role::find($roleId);

        return $role ? $role->permissions()->attach($permissionIds) : false;
    }
}
