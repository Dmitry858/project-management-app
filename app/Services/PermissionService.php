<?php

namespace App\Services;

use App\Repositories\EloquentPermissionRepository;

class PermissionService
{
    public static function hasUserPermission(int $userId, string $permSlug): bool
    {
        $repo = new EloquentPermissionRepository;
        $permissions = $repo->getUserPermissions($userId);

        foreach ($permissions as $permission)
        {
            if ($permission['slug'] === $permSlug) return true;
        }

        return false;
    }
}
