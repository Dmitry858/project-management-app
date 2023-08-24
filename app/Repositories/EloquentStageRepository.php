<?php

namespace App\Repositories;

use App\Repositories\Interfaces\StageRepositoryInterface;
use App\Models\Stage;
use Illuminate\Support\Facades\Cache;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentStageRepository implements StageRepositoryInterface
{
    use EloquentQueryBuilderHelper;

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
                $query = $this->handleFilter($query, $filter);
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
