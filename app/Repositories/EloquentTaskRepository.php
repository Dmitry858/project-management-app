<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function find(int $id)
    {
        return Task::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Task::where($filter);
        if (!empty($with)) $query = $query->with($with);

        if ($withPaginate)
        {
            return $query->paginate();
        }
        else
        {
            return $query->get();
        }
    }

    public function searchByMember(int $id, array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Task::query();
        if (!empty($with)) $query = $query->with($with);

        $query = $query->whereHas('owner', function($query) use ($id) {
            $query->where('owner_id', $id);
        })->where($filter)->orWhereHas('responsible', function($query) use ($id) {
            $query->where('responsible_id', $id);
        })->where($filter);

        return $withPaginate ? $query->paginate() : $query->get();
    }

    public function createFromArray(array $data)
    {
        return Task::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $task = $this->find($id);

        return $task ? $task->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $task = $this->find($id);

        if ($task)
        {
            if (count($task->comments) > 0)
            {
                $task->comments()->delete();
            }
            $result = $task->delete();
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
