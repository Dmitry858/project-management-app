<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function find(int $id)
    {
        return Task::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Task::query();
        if (count($filter) > 0)
        {
            foreach ($filter as $key => $value)
            {
                if ($key === 'created_at' || $key === 'updated_at')
                {
                    if (is_array($value) && $value[0] && $value[1])
                    {
                        $query = $query->whereDate($key, $value[0], $value[1]);
                    }
                }
                else if (is_array($value))
                {
                    $query = $query->whereIn($key, $value);
                }
                else
                {
                    $query = $query->where($key, $value);
                }
            }
        }

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

        $filterDate = [];
        foreach ($filter as $key => $value)
        {
            if ($key === 'created_at' || $key === 'updated_at')
            {
                $filterDate = [
                    'key' => $key,
                    'operator' => $value[0],
                    'value' => $value[1],
                ];
                unset($filter[$key]);
            }
        }

        $query = $query->whereHas('owner', function($query) use ($id) {
            $query->where('owner_id', $id);
        })->where($filter);

        if (!empty($filterDate))
        {
            $query = $query->whereDate($filterDate['key'], $filterDate['operator'], $filterDate['value']);
        }

        $query = $query->orWhereHas('responsible', function($query) use ($id) {
            $query->where('responsible_id', $id);
        })->where($filter);

        if (!empty($filterDate))
        {
            $query = $query->whereDate($filterDate['key'], $filterDate['operator'], $filterDate['value']);
        }

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

    public function delete(array $ids): bool
    {
        $tasks = $this->search(['id' => $ids], false);

        if (count($tasks) > 0)
        {
            foreach ($tasks as $task)
            {
                if (count($task->comments) > 0)
                {
                    $task->comments()->delete();
                }
                if (count($task->attachments) > 0)
                {
                    $files = [];
                    foreach ($task->attachments as $attachment)
                    {
                        $files[] = $attachment->file;
                    }
                    Storage::delete($files);
                    $task->attachments()->delete();
                }
            }
            $result = Task::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
