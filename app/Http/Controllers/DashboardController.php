<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\TaskService;
use App\Services\EventService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected $projectService;
    protected $taskService;
    protected $eventService;

    public function __construct(
        ProjectService $projectService,
        TaskService $taskService,
        EventService $eventService
    )
    {
        $this->projectService = $projectService;
        $this->taskService = $taskService;
        $this->eventService = $eventService;
    }

    public function index()
    {
        $projects = $this->projectService->getList(
            ['is_active' => 1],
            true,
        );

        $tasks = $this->taskService->getList(
            ['created_at' => ['>', Carbon::now()->modify('-7 day')]],
            true,
        );

        $todayTasks = $this->taskService->getList(
            [
                'deadline' => ['=', Carbon::today()],
            ],
            true,
        );

        $todayEvents = $this->eventService->getUserEvents(
            ['start' => ['=', Carbon::today()]],
            false
        );

        return view('dashboard', compact('projects', 'tasks', 'todayTasks', 'todayEvents'));
    }
}
