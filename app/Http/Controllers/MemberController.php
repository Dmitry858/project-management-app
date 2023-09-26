<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Services\UserService;
use App\Services\ProjectService;
use App\Http\Requests\StoreMemberRequest;
use App\Filters\MembersFilter;

class MemberController extends Controller
{
    protected MemberService $memberService;
    protected UserService $userService;
    protected ProjectService $projectService;
    protected MembersFilter $filter;

    public function __construct(
        MemberService $memberService,
        UserService $userService,
        ProjectService $projectService,
        MembersFilter $filter
    )
    {
        $this->memberService = $memberService;
        $this->userService = $userService;
        $this->projectService = $projectService;
        $this->filter = $filter;
        $this->middleware('permission:view-members')->only('index');
        $this->middleware('permission:create-members')->only(['create', 'store']);
        $this->middleware('permission:edit-members')->only(['edit', 'update']);
        $this->middleware('permission:delete-members')->only(['destroy', 'destroyGroup']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(): object
    {
        $title = __('titles.members_index');
        $members = $this->memberService->getList(
            $this->filter->params(),
            true,
            ['projects', 'user']
        );
        $projects = $this->projectService->getList([], false);

        return view('members.index', compact('title', 'members', 'projects'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $member = $this->memberService->get($id);
        $title = __('titles.members_edit', ['name' => $member->getFullName()]);
        $projects = $this->projectService->getList();

        return view('members.edit', compact('title', 'member', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $success = $this->memberService->update($id, $request->all());
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.member_updated') : __('flash.general_error');

        return redirect()->route('members.index')->with($flashKey, $flashValue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->memberService->delete([$id]);

        return redirect()->route('members.index')->with($result['status'], $result['text']);
    }

    /**
     * Remove the group of specified resources from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyGroup(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->memberService->delete($data);
        $request->session()->flash($result['status'], $result['text']);

        return response()->json($result);
    }
}
