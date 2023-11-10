<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function getUserRoles(int $userId): array;
}
