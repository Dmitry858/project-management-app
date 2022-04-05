<?php

namespace App\Services;

use App\Repositories\EloquentRoleRepository;

class RoleService
{
    public static function hasUserRole(int $userId, string $roleSlug): bool
    {
        $repo = new EloquentRoleRepository;
        $roles = $repo->getUserRoles($userId);

        foreach ($roles as $role)
        {
            if ($role['slug'] === $roleSlug) return true;
        }

        return false;
    }
}
