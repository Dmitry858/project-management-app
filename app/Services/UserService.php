<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function getList(array $filter = [], bool $withPaginate = true)
    {
        return $this->userRepository->search($filter, $withPaginate);
    }
}
