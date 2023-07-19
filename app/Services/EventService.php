<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Carbon;

class EventService
{
    protected $projectService;
    protected $eventRepository;

    public function __construct(ProjectService $projectService, EventRepositoryInterface $eventRepository)
    {
        $this->projectService = $projectService;
        $this->eventRepository = $eventRepository;
    }

    public function getUserCalendars(): array
    {
        $calendars = [
            [
                'id' => 'private',
                'name' => __('calendar.private'),
                'backgroundColor' => config('calendar.colors.private'),
            ],
            [
                'id' => 'public',
                'name' => __('calendar.public'),
                'backgroundColor' => config('calendar.colors.public'),
            ]
        ];

        $projectsCalendars = $this->getUserProjectsCalendars();

        return array_merge($calendars, $projectsCalendars);
    }

    public function getUserProjectsCalendars(): array
    {
        $calendars = [];

        $userProjects = $this->projectService->getList([], false);

        if (count($userProjects) > 0)
        {
            foreach ($userProjects as $project)
            {
                $calendars[] = [
                    'id' => 'project_'.$project->id,
                    'name' => __('calendar.project', ['name' => $project->name]),
                    'backgroundColor' => config('calendar.colors.project'),
                ];
            }
        }

        return $calendars;
    }

    public function getUserEvents($additionalFilter = [], $forCalendar = true)
    {
        if (intval(auth()->id()) === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.user_not_authorized'),
            ];
        }

        $result = [];
        $filter = [
            'user_id' => auth()->id(),
            'is_private' => 1,
        ];
        $filter = array_merge($filter, $additionalFilter);
        $privateEvents = $this->eventRepository->search($filter, false);
        if (count($privateEvents) > 0)
        {
            foreach ($privateEvents as $event)
            {
                $result[] = $forCalendar ? $this->formatEventForCalendar($event) : $event;
            }
        }

        $filter = [
            'is_private' => 0,
            'project_id' => null,
        ];
        $filter = array_merge($filter, $additionalFilter);
        $publicEvents = $this->eventRepository->search($filter, false);
        if (count($publicEvents) > 0)
        {
            foreach ($publicEvents as $event)
            {
                $result[] = $forCalendar ? $this->formatEventForCalendar($event) : $event;
            }
        }

        $userProjectsIds = $this->getUserProjectsIds();
        if (count($userProjectsIds) > 0)
        {
            $filter = [
                'is_private' => 0,
                'project_id' => $userProjectsIds,
            ];
            $filter = array_merge($filter, $additionalFilter);
            $projectEvents = $this->eventRepository->search($filter, false);
            if (count($projectEvents) > 0)
            {
                foreach ($projectEvents as $event)
                {
                    $result[] = $forCalendar ? $this->formatEventForCalendar($event) : $event;
                }
            }
        }

        if ($forCalendar)
        {
            return [
                'status' => 'success',
                'result' => $result,
            ];
        }
        else
        {
            return $result;
        }
    }

    public function getUserProjectsIds(): array
    {
        $ids = [];
        $member = auth()->user()->member;
        if ($member)
        {
            $userProjects = auth()->user()->member->projects;
            if (count($userProjects) > 0)
            {
                foreach ($userProjects as $project)
                {
                    $ids[] = $project->id;
                }
            }
        }
        return $ids;
    }

    private function formatEventForCalendar($event): array
    {
        if ($event->is_private === 0 && $event->project_id === null)
        {
            $calendarId = 'public';
        }
        else if ($event->is_private === 0 && $event->project_id !== null)
        {
            $calendarId = 'project_'.$event->project_id;
        }
        else
        {
            $calendarId = 'private';
        }

        $formattedEvent = [
            'id' => strval($event->id),
            'calendarId' => $calendarId,
            'title' => $event->title,
            'start' => $event->start,
            'end' => $event->end,
            'state' => '',
        ];

        if ($event->is_allday === 1)
        {
            $formattedEvent['isAllday'] = true;
            $formattedEvent['category'] = 'allday';
        }

        return $formattedEvent;
    }

    public function get(int $id, bool $forEditing = false)
    {
        $event = $this->eventRepository->find($id);
        if (!$event) return null;
        if ($event->is_private === 1 && $event->user_id !== auth()->id()) return null;
        if ($event->is_private === 0 && $forEditing)
        {
            $hasPermission = PermissionService::hasUserPermission(auth()->id(), 'edit-events-of-projects-and-public-events');
            if (!$hasPermission) return null;
        }

        if ($event->project_id)
        {
            $project = $this->projectService->get($event->project_id);
            if ($project) return $event;
        }
        else
        {
            return $event;
        }
    }

    public function getList(array $filter = [], bool $withPaginate = true, array $with = [], array $sort = [])
    {
        return $this->eventRepository->search($filter, $withPaginate, $with, $sort);
    }

    public function create($data): array
    {
        if (!isset($data['ajax']) && isset($data['event_type']))
        {
            $data = $this->handleEventTypeField($data);
        }
        $hasPermission = PermissionService::hasUserPermission(auth()->id(), 'create-events-of-projects-and-public-events');
        if (intval($data['is_private']) === 0 && !$hasPermission)
        {
            return [
                'status' => 'error',
                'text' => __('errors.no_permission_to_create_event')
            ];
        }

        $result = $this->validateDateFields($data);
        if ($result['status'] === 'error')
        {
            return $result;
        }

        $endDateFormat = intval($data['is_allday']) === 1 ? 'Y-m-d 23:59:59' : 'Y-m-d H:i:s';
        if (isset($data['ajax']))
        {
            $data['start'] = Carbon::parse($data['start'])->setTimezone(config('calendar.timezoneName'))->format('Y-m-d H:i:s');
            $data['end'] = Carbon::parse($data['end'])->setTimezone(config('calendar.timezoneName'))->format($endDateFormat);
            unset($data['ajax']);
        }
        else
        {
            $data['start'] = Carbon::parse($data['start'])->format('Y-m-d H:i:s');
            $data['end'] = Carbon::parse($data['end'])->format($endDateFormat);
        }

        $data['user_id'] = auth()->id();

        $event = $this->eventRepository->createFromArray($data);

        if ($event)
        {
            return [
                'status' => 'success',
                'text' => __('success_messages.event_created'),
                'id' => strval($event->id),
            ];
        }
        else
        {
            return [
                'status' => 'error',
                'text' => __('errors.general')
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        $event = $this->eventRepository->find($id);
        if (!$event)
        {
            return [
                'status' => 'error',
                'text' => __('errors.event_not_found')
            ];
        }

        $hasPermission = PermissionService::hasUserPermission(auth()->id(), 'edit-events-of-projects-and-public-events');
        if ($event->is_private === 0 && !$hasPermission)
        {
            return [
                'status' => 'error',
                'text' => __('errors.no_permission_to_edit_event')
            ];
        }

        $result = $this->validateDateFields($data, $event);
        if ($result['status'] === 'error')
        {
            return $result;
        }

        if (isset($data['event_type']))
        {
            $data = $this->handleEventTypeField($data);
        }

        if (isset($data['is_private']))
        {
            if (intval($data['is_private']) === 1 && intval($event->project_id) > 0)
            {
                $data['project_id'] = null;
            }
            else if (intval($data['is_private']) === 0 && !isset($data['project_id']) && intval($event->project_id) > 0)
            {
                $data['project_id'] = null;
            }
        }

        if (isset($data['start']))
        {
            if (isset($data['ajax']))
            {
                $data['start'] = Carbon::parse($data['start'])->setTimezone(config('calendar.timezoneName'))->format('Y-m-d H:i:s');
            }
            else
            {
                $data['start'] = Carbon::parse($data['start'])->format('Y-m-d H:i:s');
            }
        }
        if (isset($data['end']))
        {
            $endDateFormat = 'Y-m-d H:i:s';
            if ((isset($data['is_allday']) && intval($data['is_allday']) === 1)
                || (!isset($data['is_allday']) && $event->is_allday === 1))
            {
                $endDateFormat = 'Y-m-d 23:59:59';
            }
            if (isset($data['ajax']))
            {
                $data['end'] = Carbon::parse($data['end'])->setTimezone(config('calendar.timezoneName'))->format($endDateFormat);
            }
            else
            {
                $data['end'] = Carbon::parse($data['end'])->format($endDateFormat);
            }
        }

        $success = $this->eventRepository->updateFromArray($id, $data);

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.event_updated') : __('flash.general_error')
        ];
    }

    private function handleEventTypeField(array $data): array
    {
        $data['is_private'] = $data['event_type'] === 'private' ? 1 : 0;
        if (strpos($data['event_type'], 'project_') !== false)
        {
            $data['project_id'] = intval(str_replace('project_', '', $data['event_type']));
        }
        unset($data['event_type']);

        return $data;
    }

    private function validateDateFields(array $data, $event = null): array
    {
        if (isset($data['ajax']) && intval($data['ajax']) === 1)
        {
            if (!isset($data['is_allday']) && $event)
            {
                $data['is_allday'] = $event->is_allday;
            }

            if (!isset($data['start']) && $event)
            {
                $data['start'] = $event->start;
            }
            else if (isset($data['start']))
            {
                $data['start'] = Carbon::parse($data['start'])->setTimezone(config('calendar.timezoneName'))->format('Y-m-d H:i:s');
            }
            if (!isset($data['end']) && $event)
            {
                $data['end'] = $event->end;
            }
            else if (isset($data['end']))
            {
                $data['end'] = Carbon::parse($data['end'])->setTimezone(config('calendar.timezoneName'))->format('Y-m-d H:i:s');;
            }
        }

        $isAllday = intval($data['is_allday']) === 1;
        $startDateTs = strtotime($data['start']);
        $endDateTs = strtotime($data['end']);

        if (($endDateTs <= $startDateTs && !$isAllday) || ($endDateTs < $startDateTs && $isAllday))
        {
            return [
                'status' => 'error',
                'text' => __('errors.end_date_greater_than_start')
            ];
        }

        if (!$isAllday)
        {
            $separator = strpos($data['start'], 'T') !== false ? 'T' : ' ';
            $arStartDate = explode($separator, $data['start']);
            $arEndDate = explode($separator, $data['end']);
            if ($arStartDate[0] !== $arEndDate[0])
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.one_day_time')
                ];
            }
        }

        return [
            'status' => 'success',
        ];
    }

    public function delete(array $ids): array
    {
        if (isset($ids['ids']) && is_array($ids['ids'])) $ids = $ids['ids'];
        $hasPermission = PermissionService::hasUserPermission(auth()->id(), 'delete-events-of-projects-and-public-events');
        $events = $this->eventRepository->search(['id' => $ids], false);

        if (count($events) === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.event_not_found')
            ];
        }

        foreach ($events as $event)
        {
            if (($event->is_private === 0 && !$hasPermission)
                || ($event->is_private === 1 && auth()->id() !== $event->user_id))
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.no_permission_to_delete_event')
                ];
            }
        }

        $success = $this->eventRepository->delete($ids);

        $successMsg = count($ids) > 1 ? __('flash.events_deleted') : __('flash.event_deleted');

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? $successMsg : __('flash.general_error')
        ];
    }

    public function getFilterForEventsList(array $filterParams): array
    {
        $filter = [
            'or' => []
        ];
        $allParams = [
            'private' => [
                'user_id' => auth()->id(),
                'is_private' => 1,
            ],
            'public' => [
                'is_private' => 0,
                'project_id' => null,
            ],
            'project' => [
                'is_private' => 0,
                'project_id' => [],
            ],
        ];

        foreach ($allParams as $key => $params)
        {
            if (isset($filterParams['event_type']) && $filterParams['event_type'] !== $key)
            {
                continue;
            }
            if ($key === 'project')
            {
                $userProjectsIds = $this->getUserProjectsIds();
                if (count($userProjectsIds) > 0)
                {
                    $params['project_id'] = $userProjectsIds;
                }
                else
                {
                    continue;
                }
            }
            if (isset($filterParams['is_allday']))
            {
                $params['is_allday'] = intval($filterParams['is_allday']);
            }
            if (isset($filterParams['event_start']))
            {
                if ($filterParams['event_start'] === 'today')
                {
                    $params['start'] = ['=', Carbon::today()];
                }
                else if ($filterParams['event_start'] === 'tomorrow')
                {
                    $params['start'] = ['=', Carbon::tomorrow()];
                }
                else if ($filterParams['event_start'] === 'week')
                {
                    $params['start'] = [
                        'between',
                        [
                            Carbon::parse('last Monday'),
                            Carbon::parse('last Monday')->modify('+7 day'),
                        ]
                    ];
                }
                else if ($filterParams['event_start'] === 'month')
                {
                    $params['start'] = [
                        'between',
                        [
                            Carbon::parse(date('Y-m-01')),
                            Carbon::parse(date('Y-m-t 23:59:59')),
                        ]
                    ];
                }
            }

            $filter['or'][] = $params;
        }

        return $filter;
    }
}
