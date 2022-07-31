<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\ProjectService;
use App\Services\MemberService;
use App\Services\StageService;

class TaskController extends Controller
{
    protected $taskService;
    protected $projectService;
    protected $memberService;
    protected $stageService;

    public function __construct(
        TaskService $taskService,
        ProjectService $projectService,
        MemberService $memberService,
        StageService $stageService
    )
    {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
        $this->memberService = $memberService;
        $this->stageService = $stageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.tasks_index');
        $tasks = $this->taskService->getList();

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
