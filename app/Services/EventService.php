<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;

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

    public function getUserEvents()
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
        $privateEvents = $this->eventRepository->search($filter, false);
        if (count($privateEvents) > 0)
        {
            foreach ($privateEvents as $event)
            {
                $result[] = $this->formatEventForCalendar($event);
            }
        }

        $filter = [
            'is_private' => 0,
            'project_id' => null,
        ];
        $publicEvents = $this->eventRepository->search($filter, false);
        if (count($publicEvents) > 0)
        {
            foreach ($publicEvents as $event)
            {
                $result[] = $this->formatEventForCalendar($event);
            }
        }

        $member = auth()->user()->member;
        if ($member)
        {
            $userProjects = auth()->user()->member->projects;

            if (count($userProjects) > 0)
            {
                $userProjectsIds = [];
                foreach ($userProjects as $project)
                {
                    $userProjectsIds[] = $project->id;
                }

                $filter = [
                    'is_private' => 0,
                    'project_id' => $userProjectsIds,
                ];
                $projectEvents = $this->eventRepository->search($filter, false);
                if (count($projectEvents) > 0)
                {
                    foreach ($projectEvents as $event)
                    {
                        $result[] = $this->formatEventForCalendar($event);
                    }
                }
            }
        }

        return [
            'status' => 'success',
            'result' => $result,
        ];
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
            'id' => $event->id,
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
}
