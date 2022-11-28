<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvitationService;
use App\Http\Requests\StoreInvitationRequest;

class InvitationController extends Controller
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.invitations_index');
        $invitations = $this->invitationService->getList();

        return view('invitations.index', compact('title', 'invitations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.invitations_create');

        return view('invitations.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreInvitationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreInvitationRequest $request)
    {
        $result = $this->invitationService->create($request->all());

        return redirect()->route('invitations.index')->with($result['status'], $result['text']);
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
