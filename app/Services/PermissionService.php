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

    public static function hasUserPermission(int|null $userId, string $permSlug): bool
    {
        if (!$userId) return false;
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

    public function getUserEventsPermissions(): array
    {
        $permissions = $this->permissionRepository->getUserPermissions(auth()->id());

        if (count($permissions) === 0) return [];

        $eventsPermissions = [];
        foreach ($permissions as $permission)
        {
            if (strpos($permission['slug'], 'events') !== false)
            {
                $eventsPermissions[] = $permission['slug'];
            }
        }

        return $eventsPermissions;
    }
}
