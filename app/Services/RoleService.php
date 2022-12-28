<?php

namespace App\Services;

use App\Repositories\EloquentRoleRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function get(int $id)
    {
        return $this->roleRepository->find($id);
    }

    public function getList(array $filter = [])
    {
        return $this->roleRepository->search($filter);
    }

    public function update(int $id, array $data): array
    {
        unset($data['_token']);
        unset($data['_method']);

        if ($id === 1)
        {
            return [
                'status' => 'error',
                'text' => __('errors.change_admin_role_forbidden')
            ];
        }

        foreach ($data['permissions'] as $permission)
        {
            if (in_array($permission, config('app.permissions_only_for_admin')))
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.there_are_invalid_permissions')
                ];
            }
        }

        $permissionIds = $data['permissions'];
        unset($data['permissions']);

        $success = $this->roleRepository->updateFromArray($id, $data);

        if ($success)
        {
            if (is_array($permissionIds))
            {
                $this->roleRepository->syncingPermissions($id, $permissionIds);
            }

            Cache::flush();
        }

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.role_updated') : __('flash.general_error')
        ];
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

    public function create($data)
    {
        unset($data['_token']);

        if (array_key_exists('permissions', $data) && is_array($data['permissions']))
        {
            $permissionIds = $data['permissions'];
            unset($data['permissions']);

            foreach ($permissionIds as $id)
            {
                if (in_array($id, config('app.permissions_only_for_admin')))
                {
                    return [
                        'status' => 'error',
                        'text' => __('errors.there_are_invalid_permissions')
                    ];
                }
            }
        }

        $newRole = $this->roleRepository->createFromArray($data);

        if ($newRole && Cache::has('all_roles'))
        {
            Cache::forget('all_roles');
        }

        if ($newRole && isset($permissionIds) && is_array($permissionIds))
        {
            $this->roleRepository->attachPermissions($newRole->id, $permissionIds);
        }

        return [
            'status' => $newRole ? 'success' : 'error',
            'text' => $newRole ? __('flash.role_created') : __('flash.general_error')
        ];
    }
}
