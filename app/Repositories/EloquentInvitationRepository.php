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

    public function updateSendingStatus(int $id, int $status): bool
    {
        $invitation = $this->find($id);

        return $invitation ? $invitation->update(['is_sent' => $status]) : false;
    }

    public function delete(int $id): bool
    {
        $invitation = $this->find($id);

        return $invitation ? $invitation->delete() : false;
    }
}
