<?php

namespace App\Repositories;

use App\Repositories\Interfaces\InvitationRepositoryInterface;
use App\Models\Invitation;

class EloquentInvitationRepository implements InvitationRepositoryInterface
{
    public function find(int $id)
    {
        return Invitation::find($id);
    }

    public function findByKey(string $key)
    {
        return Invitation::where('secret_key', $key)->first();
    }

    public function search(array $filter = [], bool $withPaginate = true)
    {
        if ($withPaginate)
        {
            return Invitation::where($filter)->paginate();
        }
        else
        {
            return Invitation::where($filter)->get();
        }
    }

    public function createFromArray(array $data)
    {
        return Invitation::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $invitation = $this->find($id);

        return $invitation ? $invitation->update($data) : false;
    }

    public function delete(array $ids): bool
    {
        return Invitation::destroy($ids);
    }
}
