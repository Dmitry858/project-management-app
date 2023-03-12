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
            $query = Stage::query();
            if (count($filter) > 0)
            {
                foreach ($filter as $key => $value)
                {
                    if (is_array($value))
                    {
                        $query = $query->whereIn($key, $value);
                    }
                    else
                    {
                        $query = $query->where($key, $value);
                    }
                }
            }
            $stages = $query->get();
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

    public function delete(array $ids): bool
    {
        $stages = $this->search(['id' => $ids]);

        if (count($stages) > 0)
        {
            $result = Stage::destroy($ids);
        }
        else
        {
            $result = false;
        }
        return $result;
    }

    public function getCount(): int
    {
        return Stage::all()->count();
    }
}
