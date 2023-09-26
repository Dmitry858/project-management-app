<?php

namespace App\Filters;

class MembersFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'project_id',
    ];
}
