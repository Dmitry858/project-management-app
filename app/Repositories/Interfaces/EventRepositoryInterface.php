<?php

namespace App\Repositories\Interfaces;

interface EventRepositoryInterface
{
    public function find(int $id);

    public function search(array $filter = []);

    public function createFromArray(array $data);

    public function updateFromArray(int $id, array $data): bool;

    public function delete(array $ids): bool;
}
