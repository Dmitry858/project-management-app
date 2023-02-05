<?php

namespace App\Repositories\Interfaces;

interface AttachmentRepositoryInterface
{
    public function find(int $id);

    public function search(array $filter = []);

    public function createFromArray(array $data);

    public function delete(array $ids);
}
