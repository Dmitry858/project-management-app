<?php

namespace App\Repositories\Interfaces;

interface InvitationRepositoryInterface
{
    public function find(int $id);

    public function search(array $filter = []);

    public function createFromArray(array $data);

    public function updateSendingStatus(int $id, int $status): bool;

    public function delete(array $ids): bool;
}
