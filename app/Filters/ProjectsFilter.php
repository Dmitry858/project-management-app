<?php

namespace App\Filters;

class ProjectsFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'is_active',
    ];
}
