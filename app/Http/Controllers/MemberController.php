<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Services\UserService;
use App\Services\ProjectService;
use App\Http\Requests\StoreMemberRequest;

class MemberController extends Controller
{
    protected $memberService;
    protected $userService;
    protected $projectService;

    public function __construct(MemberService $memberService, UserService $userService, ProjectService $projectService)
    {
        $this->memberService = $memberService;
        $this->userService = $userService;
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(): object
    {
        $title = __('titles.members_index');
        $members = $this->memberService->getList();

        return view('members.index', compact('title', 'members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.members_create');
        $users = $this->userService->getList(['is_active' => 1, 'member' => false], false);
        $projects = $this->projectService->getList(['is_active' => 1]);

        return view('members.create', compact('title', 'users', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreMemberRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreMemberRequest $request)
    {
        $success = $this->memberService->create($request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.member_created') : __('flash.general_error');

        return redirect()->route('members.index')->with($flashKey, $flashValue);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $success = $this->memberService->delete($id);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.member_deleted') : __('flash.general_error');

        return redirect()->route('members.index')->with($flashKey, $flashValue);
    }
}
