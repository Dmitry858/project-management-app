<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventService;
use App\Services\PermissionService;
use App\Http\Requests\StoreEventRequest;

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
            $result = $this->eventService->getUserEvents();
            return response()->json($result);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function store(StoreEventRequest $request)
    {
        $isAjax = boolval($request->query('ajax', '0'));

        if ($isAjax)
        {
            $result = $this->eventService->create($request->all());
            return response()->json($result);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
