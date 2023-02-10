<?php

namespace App\Filters;

class UsersFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'is_active',
    ];
}
