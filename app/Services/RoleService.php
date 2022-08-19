<?php

namespace App\Services;

use App\Repositories\EloquentRoleRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getList(array $filter = [])
    {
        return $this->roleRepository->search($filter);
    }

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
