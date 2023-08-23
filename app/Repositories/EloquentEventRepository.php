<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Traits\EloquentQueryBuilderHelper;

class EloquentEventRepository implements EventRepositoryInterface
{
    use EloquentQueryBuilderHelper;

    public function find(int $id)
    {
        return Event::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [], array $sort = [])
    {
        $query = Event::query();
        if (count($filter) > 0)
        {
            $query = $this->handleFilter($query, $filter);
        }

        if (!empty($with)) $query = $query->with($with);

        if (!empty($sort)) $query = $query->orderBy($sort['column'], $sort['direction']);

        if ($withPaginate)
        {
            return $query->paginate();
        }
        else
        {
            return $query->get();
        }
    }

    public function createFromArray(array $data)
    {
        return Event::create($data);
    }

    public function updateFromArray(int $id, array $data): bool
    {
        $event = $this->find($id);

        return $event ? $event->update($data) : false;
    }

    public function delete(array $ids): bool
    {
        $events = $this->search(['id' => $ids], false);

        if (count($events) > 0)
        {
            $result = Event::destroy($ids);
        }
        else
        {
            $result = false;
        }

        return $result;
    }
}
