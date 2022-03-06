<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function find(int $id)
    {
        return User::find($id);
    }

    public function findOrCreateMember(object $user)
    {
        return $user->member()->firstOrCreate([
            'user_id' => $user->id
        ]);
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        if ($withPaginate)
        {
            return User::where($filter)->paginate();
        }
        else
        {
            return User::where($filter)->get();
        }
    }

    public function createFromArray(array $data)
    {
        $user = User::create($data);

        return $user;
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $user = $this->find($id);

        return $user ? $user->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);

        return $user ? $user->delete() : false;
    }
}
