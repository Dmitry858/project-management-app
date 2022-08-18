<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function getUserRoles(int $userId): array;

    public function search(array $filter = []);
}
