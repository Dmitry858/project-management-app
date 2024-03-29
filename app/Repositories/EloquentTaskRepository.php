<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Task::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Task::query();
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
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

        $query = $query->whereHas('owner', function($query) use ($id) {
            $query->where('owner_id', $id);
        });
        $query = $this->handleFilter($query, $filter);

        $query = $query->orWhereHas('responsible', function($query) use ($id) {
            $query->where('responsible_id', $id);
        });
        $query = $this->handleFilter($query, $filter);

        if (!empty($with)) $query = $query->with($with);

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
