<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    protected $projectService;
    protected $userService;

    public function __construct(ProjectService $projectService, UserService $userService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.projects_index');
        $projects = $this->projectService->getList();

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
        $title = __('titles.projects_single', ['name' => $project->name]);
        $members = $this->projectService->getProjectMembers($project);

        return view('projects.single', compact('title', 'project', 'members'));
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
        $title = __('titles.projects_edit', ['name' => $project->name]);
        $users = $this->userService->getList(['is_active' => 1], false);

        return view('projects.edit', compact('title', 'project', 'users'));
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
        $success = $this->projectService->delete($id);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.project_deleted') : __('flash.general_error');

        return redirect()->route('projects.index')->with($flashKey, $flashValue);
    }
}
