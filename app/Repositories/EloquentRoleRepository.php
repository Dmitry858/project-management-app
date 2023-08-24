<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Role;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    use EloquentQueryBuilderHelper;

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
            $query = Role::query();
            if (count($filter) > 0)
            {
                $query = $this->handleFilter($query, $filter);
            }
            $roles = $query->get();
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

    public function delete(array $ids): bool
    {
        $roles = $this->search(['id' => $ids]);

        if (count($roles) > 0)
        {
            foreach ($roles as $role)
            {
                if (count($role->permissions) > 0)
                {
                    $role->permissions()->detach();
                }
                if (count($role->users) > 0)
                {
                    $role->users()->detach();
                }
            }

            $result = Role::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
