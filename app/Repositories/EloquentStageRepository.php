<?php

namespace App\Repositories;

use App\Repositories\Interfaces\StageRepositoryInterface;
use App\Models\Stage;

class EloquentStageRepository implements StageRepositoryInterface
{
    public function find(int $id)
    {
        return Stage::find($id);
    }

    public function search(array $filter = [])
    {
        return Stage::where($filter)->get();
    }

    public function createFromArray(array $data)
    {
        return Stage::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $stage = $this->find($id);

        return $stage ? $stage->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $stage = $this->find($id);

        return $stage ? $stage->delete() : false;
    }
}
