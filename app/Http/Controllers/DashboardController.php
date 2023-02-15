<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\TaskService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected $projectService;
    protected $taskService;

    public function __construct(ProjectService $projectService, TaskService $taskService)
    {
        $this->projectService = $projectService;
        $this->taskService = $taskService;
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

        return view('dashboard', compact('projects', 'tasks'));
    }
}
