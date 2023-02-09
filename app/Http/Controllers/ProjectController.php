<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\UserService;
use App\Services\TaskService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Filters\ProjectsFilter;

class ProjectController extends Controller
{
    protected $projectService;
    protected $userService;
    protected $taskService;
    protected $filter;

    public function __construct(
        ProjectService $projectService,
        UserService $userService,
        TaskService $taskService,
        ProjectsFilter $filter
    )
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->filter = $filter;
        $this->middleware('permission:create-projects')->only(['create', 'store']);
        $this->middleware('permission:edit-projects')->only(['edit', 'update']);
        $this->middleware('permission:delete-projects')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.projects_index');
        $projects = $this->projectService->getList(
            $this->filter->params(),
            true,
            ['members']
        );

        return view('projects.index', compact('title', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.projects_create');
        $users = $this->userService->getList(['is_active' => 1], false);

        return view('projects.create', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProjectRequest $request)
    {
        $success = $this->projectService->create($request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.project_created') : __('flash.general_error');

        return redirect()->route('projects.index')->with($flashKey, $flashValue);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show($id)
    {
        $project = $this->projectService->get($id);

        if ($project)
        {
            $title = __('titles.projects_single', ['name' => $project->name]);
            $members = $this->projectService->getProjectMembers($project);
            $tasks = $this->taskService->getList(['project_id' => $id]);

            return view('projects.single', compact('title', 'project', 'members', 'tasks'));
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
        $project = $this->projectService->get($id);

        if ($project)
        {
            $title = __('titles.projects_edit', ['name' => $project->name]);
            $users = $this->userService->getList(['is_active' => 1], false);

            return view('projects.edit', compact('title', 'project', 'users'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProjectRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProjectRequest $request, int $id)
    {
        $success = $this->projectService->update($id, $request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.project_updated') : __('flash.general_error');

        return redirect()->route('projects.index')->with($flashKey, $flashValue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->projectService->delete($id);

        return redirect()->route('projects.index')->with($result['status'], $result['text']);
    }
}
