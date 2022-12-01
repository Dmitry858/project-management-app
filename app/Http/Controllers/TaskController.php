<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Services\ProjectService;
use App\Services\MemberService;
use App\Services\StageService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Filters\TasksFilter;

class TaskController extends Controller
{
    protected $taskService;
    protected $projectService;
    protected $memberService;
    protected $stageService;
    protected $filter;

    public function __construct(
        TaskService $taskService,
        ProjectService $projectService,
        MemberService $memberService,
        StageService $stageService,
        TasksFilter $filter
    )
    {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->memberService = $memberService;
        $this->stageService = $stageService;
        $this->filter = $filter;
        $this->middleware('permission:create-tasks')->only(['create', 'store']);
        $this->middleware('permission:edit-tasks')->only(['edit', 'update']);
        $this->middleware('permission:delete-tasks')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.tasks_index');
        $tasks = $this->taskService->getList(
            $this->filter->params(),
            true,
            ['project', 'stage', 'owner', 'responsible']
        );

        return view('tasks.index', compact('title', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.tasks_create');
        $projects = $this->projectService->getList(['is_active' => 1]);
        $members = $this->memberService->getList();
        $stages = $this->stageService->getList();

        return view('tasks.create', compact('title', 'projects', 'members', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $success = $this->taskService->create($request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.task_created') : __('flash.general_error');

        return redirect()->route('tasks.index')->with($flashKey, $flashValue);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $task = $this->taskService->get($id);
        if ($task)
        {
            $title = __('titles.tasks_single', ['name' => $task->name]);
            $stages = $this->stageService->getList();

            return view('tasks.single', compact('title', 'task', 'stages'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $task = $this->taskService->get($id);
        if ($task)
        {
            $title = __('titles.tasks_edit', ['name' => $task->name]);
            $projects = $this->projectService->getList(['is_active' => 1]);
            $members = $this->memberService->getList();
            $stages = $this->stageService->getList();
            return view('tasks.edit', compact('title', 'task', 'projects', 'members', 'stages'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateTaskRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $success = $this->taskService->update($id, $request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.task_updated') : __('flash.general_error');

        return redirect()->route('tasks.index')->with($flashKey, $flashValue);
    }

    /**
     * Update the stage of task in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStage(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->taskService->update($id, $data, true);

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $success = $this->taskService->delete($id);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.task_deleted') : __('flash.general_error');

        return redirect()->route('tasks.index')->with($flashKey, $flashValue);
    }
}
