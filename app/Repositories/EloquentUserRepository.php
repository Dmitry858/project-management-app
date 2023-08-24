<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentUserRepository implements UserRepositoryInterface
{
    use EloquentQueryBuilderHelper;

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

    public function hasMember(int $userId): bool
    {
        $user = $this->find($userId);

        return $user->member()->first() ? true : false;
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        $query = User::query();
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }

        if ($withPaginate)
        {
            return $query->paginate();
        }
        else
        {
            return $query->get();
        }
    }

    public function createFromArray(array $data)
    {
        if (array_key_exists('roles', $data))
        {
            $roleIds = $data['roles'];
            unset($data['roles']);
        }
        $user = User::create($data);

        if ($user && isset($roleIds))
        {
            $user->roles()->attach($roleIds);
        }

        return $user;
    }

    public function updateFromArray(int $id, array $data, bool $isProfile = false): bool
    {
        $user = $this->find($id);

        if (!$user) return false;

        if ($this->hasMember($id) && Cache::has('member_'.$user->member->id.'_fullname'))
        {
            Cache::forget('member_'.$user->member->id.'_fullname');
        }

        if (array_key_exists('roles', $data))
        {
            $roleIds = $data['roles'];
            unset($data['roles']);
        }
        $result = $user->update($data);

        if ($result && isset($roleIds) && !$isProfile)
        {
            $user->roles()->sync($roleIds);
        }
        elseif ($result && !isset($roleIds) && !$isProfile)
        {
            $user->roles()->detach();
        }

        return $result;
    }

    public function delete(array $ids): bool
    {
        $users = $this->search(['id' => $ids], false);

        if (count($users) > 0)
        {
            foreach ($users as $user)
            {
                if (count($user->roles) > 0)
                {
                    $user->roles()->detach();
                }
            }
            $result = User::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
