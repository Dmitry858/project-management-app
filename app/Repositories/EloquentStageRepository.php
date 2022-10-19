<?php

namespace App\Repositories;

use App\Repositories\Interfaces\StageRepositoryInterface;
use App\Models\Stage;
use Illuminate\Support\Facades\Cache;

class EloquentStageRepository implements StageRepositoryInterface
{
    public function find(int $id)
    {
        return Stage::find($id);
    }

    public function search(array $filter = [])
    {
        if (empty($filter) && Cache::has('all_stages'))
        {
            $stages = Cache::get('all_stages');
        }
        else
        {
            $stages = Stage::where($filter)->get();
            if (empty($filter))
            {
                Cache::put('all_stages', $stages, 14400);
            }
        }

        return $stages;
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

    public function getCount(): int
    {
        return Stage::all()->count();
    }
}
