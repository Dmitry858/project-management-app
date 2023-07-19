<?php

namespace App\Filters;

class EventsFilter extends QueryFilter
{
    protected static array $onlyParams = [
        'event_type',
        'event_start',
        'is_allday',
    ];
}
