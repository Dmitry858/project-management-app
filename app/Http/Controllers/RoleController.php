<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Services\PermissionService;

class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.roles_index');
        $roles = $this->roleService->getList();

        return view('roles.index', compact('title', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.roles_create');
        $permissions = $this->permissionService->getList();

        return view('roles.create', compact('title', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $result = $this->roleService->create($request->all());

        return redirect()->route('roles.index')->with($result['status'], $result['text']);
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
        $role = $this->roleService->get($id);

        if ($role && $role->slug !== 'admin')
        {
            $title = __('titles.roles_edit', ['name' => $role->name]);
            $permissions = $this->permissionService->getList();
        }
        else
        {
            abort(404);
        }

        return view('roles.edit', compact('title', 'role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoleRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $result = $this->roleService->update($id, $request->all());

        return redirect()->route('roles.index')->with($result['status'], $result['text']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->roleService->delete($id);

        return redirect()->route('roles.index')->with($result['status'], $result['text']);
    }
}
