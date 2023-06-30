<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Models\Event;

class EloquentEventRepository implements EventRepositoryInterface
{
    public function find(int $id)
    {
        return Event::find($id);
    }

    public function search(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        $query = Event::query();
        if (count($filter) > 0)
        {
            foreach ($filter as $key => $value)
            {
                if ($key === 'start' || $key === 'end')
                {
                    if (is_array($value) && $value[0] && $value[1])
                    {
                        if ($value[0] === 'between')
                        {
                            $query = $query->whereBetween($key, $value[1]);
                        }
                        else
                        {
                            $query = $query->whereDate($key, $value[0], $value[1]);
                        }
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
