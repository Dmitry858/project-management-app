<?php

namespace App\Repositories\Interfaces;

interface PermissionRepositoryInterface
{
    public function getUserPermissions(int $userId): array;
}
