<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    public function getUserPermissions(int $userId): array
    {
        if (Cache::has('user_'.$userId.'_permissions'))
        {
            $permissions = Cache::get('user_'.$userId.'_permissions');
        }
        else
        {
            $user = User::find($userId);
            $permissions = $user ? $user->permissions()->get()->toArray() : [];
            $roles = $user->roles()->get();
            foreach ($roles as $role)
            {
                $permThroughRole = $role->permissions()->get()->toArray();
                foreach ($permThroughRole as $item)
                {
                    $key = array_search($item['id'], array_column($permissions, 'id'));
                    if ($key === false)
                    {
                        $permissions[] = $item;
                    }
                }
            }
            Cache::put('user_'.$userId.'_permissions', $permissions, 180);
        }

        return $permissions;
    }
}
