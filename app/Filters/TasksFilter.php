<?php

namespace App\Filters;

class TasksFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'project_id',
        'stage_id',
        'is_active',
    ];
}
