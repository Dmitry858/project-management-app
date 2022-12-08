<?php

namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\EloquentPermissionRepository;

class PermissionService
{
    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

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

    public function getList(array $filter = [])
    {
        return $this->permissionRepository->search($filter);
    }
}
