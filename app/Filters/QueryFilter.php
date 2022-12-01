<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;
    protected static array $onlyParams = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function params(): array
    {
        $params = $this->request->query();

        if (!empty($params) && !empty(static::$onlyParams))
        {
            foreach ($params as $key => $value)
            {
                if (!in_array($key, static::$onlyParams))
                {
                    unset($params[$key]);
                }
            }
        }

        return $params;
    }
}
