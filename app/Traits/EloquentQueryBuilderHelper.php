<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait EloquentQueryBuilderHelper
{
    public array $dateFieldKeys = [
        'start',
        'end',
        'created_at',
        'updated_at',
        'deadline',
    ];

    public function handleFilter(Builder $query, array $filter): Builder
    {
        foreach ($filter as $key => $value)
        {
            if ($key === 'or')
            {
                foreach ($value as $i => $conditions)
                {
                    if ($i === 0)
                    {
                        foreach ($conditions as $condKey => $condValue)
                        {
                            $query = $this->buildQuery($query, $condKey, $condValue);
                        }
                    }
                    else
                    {
                        $query->orWhere(function($query) use ($conditions) {
                            foreach ($conditions as $condKey => $condValue)
                            {
                                $query = $this->buildQuery($query, $condKey, $condValue);
                            }
                        });
                    }
                }
            }
            else
            {
                $query = $this->buildQuery($query, $key, $value);
            }
        }

        return $query;
    }

    private function buildQuery(Builder $query, string $key, array|string|int|null $value): Builder
    {
        if (in_array($key, $this->dateFieldKeys) && (is_array($value) && $value[0] && $value[1]))
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
        else if ($key === 'name')
        {
            $query = $query->where($key, 'like', '%'.$value.'%');
        }
        else if ($key === 'member' && !$value)
        {
            $query = $query->doesntHave($key);
        }
        else if (is_array($value))
        {
            $query = $query->whereIn($key, $value);
        }
        else
        {
            $query = $query->where($key, $value);
        }

        return $query;
    }
}
