<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

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
            Cache::put('user_'.$userId.'_roles', $roles, 30);
        }

        return $roles;
    }
}
