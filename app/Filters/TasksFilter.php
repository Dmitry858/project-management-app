<?php

namespace App\Filters;

class TasksFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'project_id',
        'deadline',
        'stage_id',
        'is_active',
    ];
}
