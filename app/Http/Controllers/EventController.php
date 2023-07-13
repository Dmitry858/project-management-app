<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventService;
use App\Services\PermissionService;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    protected $eventService;
    protected $permissionService;

    public function __construct(EventService $eventService, PermissionService $permissionService)
    {
        $this->eventService = $eventService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a calendar with events.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function calendar()
    {
        $title = __('titles.calendar');
        $calendars = $this->eventService->getUserCalendars();
        $permissions = $this->permissionService->getUserEventsPermissions();

        return view('calendar', compact('title', 'calendars', 'permissions'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $isAjax = boolval($request->query('ajax', '0'));

        if ($isAjax)
        {
            $filter = [];

            if ($request->query('from') && $request->query('to'))
            {
                $filter['start'] = [
                    'between',
                    [
                        Carbon::parse($request->query('from')),
                        Carbon::parse($request->query('to').' 23:59:59'),
                    ]
                ];
            }

            $result = $this->eventService->getUserEvents($filter);
            return response()->json($result);
        }
        else
        {
            $title = __('titles.events_index');
            $filter = [
                'or' => [
                    [
                        'user_id' => auth()->id(),
                        'is_private' => 1,
                    ],
                    [
                        'is_private' => 0,
                        'project_id' => null,
                    ],
                ],
            ];

            $userProjectsIds = $this->eventService->getUserProjectsIds();
            if (count($userProjectsIds) > 0)
            {
                $filter['or'][] = [
                    'is_private' => 0,
                    'project_id' => $userProjectsIds,
                ];
            }
            $sort = [
                'column' => 'start',
                'direction' => 'desc',
            ];
            $events = $this->eventService->getList($filter, true, [], $sort);

            return view('events.index', compact('title', 'events'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.events_create');
        $projects = $this->eventService->getUserProjectsCalendars();

        return view('events.create', compact('title', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(StoreEventRequest $request)
    {
        $isAjax = boolval($request->query('ajax', '0'));

        if ($isAjax)
        {
            $result = $this->eventService->create($request->all());
            return response()->json($result);
        }
        else
        {
            $result = $this->eventService->create($request->all());
            $route = $result['status'] === 'error' ? 'events.create' : 'events.index';
            return redirect()->route($route)->with($result['status'], $result['text']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $event = $this->eventService->get($id);

        if (!$event) abort(404);

        $title = __('titles.events_single', ['title' => $event->title]);

        return view('events.single', compact('title', 'event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $event = $this->eventService->get($id, true);

        if (!$event) abort(404);

        $title = __('titles.events_edit', ['title' => $event->title]);
        $projects = $this->eventService->getUserProjectsCalendars();

        return view('events.edit', compact('title', 'event', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateEventRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $isAjax = boolval($request->query('ajax', '0'));

        if ($isAjax)
        {
            $result = $this->eventService->update($id, $request->all());
            return response()->json($result);
        }
        else
        {
            $result = $this->eventService->update($id, $request->all());

            if ($result['status'] === 'error')
            {
                return redirect()->route('events.edit', ['event' => $id])->with($result['status'], $result['text']);
            }
            else
            {
                return redirect()->route('events.index')->with($result['status'], $result['text']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $isAjax = boolval($request->query('ajax', '0'));
        $result = $this->eventService->delete([$id]);

        if ($isAjax)
        {
            return response()->json($result);
        }
        else
        {
            return redirect()->route('events.index')->with($result['status'], $result['text']);
        }
    }

    /**
     * Remove the group of specified resources from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyGroup(Request $request)
    {
        //
    }
}
