<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\RoleService;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Filters\UsersFilter;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;
    protected $filter;

    public function __construct(UserService $userService, RoleService $roleService, UsersFilter $filter)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->filter = $filter;
        $this->middleware('permission:view-users')->only(['index']);
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:edit-users')->only(['edit', 'update']);
        $this->middleware('permission:delete-users')->only(['destroy', 'destroyGroup']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.users_index');
        $users = $this->userService->getList($this->filter->params());

        return view('users.index', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.users_create');
        $roles = $this->roleService->getList();

        return view('users.create', compact('title', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $success = $this->userService->create($request);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.user_created') : __('flash.general_error');

        return redirect()->route('users.index')->with($flashKey, $flashValue);
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $user = $this->userService->get($id);
        if ($user)
        {
            $name = $user->name;
            if ($user->last_name) $name .= ' '.$user->last_name;
            $title = __('titles.users_edit', ['name' => $name]);
            $roles = $this->roleService->getList();
            return view('users.edit', compact('title', 'user', 'roles'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $success = $this->userService->update($id, $request);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.user_updated') : __('flash.general_error');

        return redirect()->route('users.index')->with($flashKey, $flashValue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->userService->delete([$id]);

        return redirect()->route('users.index')->with($result['status'], $result['text']);
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
        $result = $this->userService->delete($data);
        $request->session()->flash($result['status'], $result['text']);

        return response()->json($result);
    }
}
