<?php

namespace App\Http\Controllers;

use App\Services\InvitationService;
use App\Http\Requests\StoreInvitationRequest;
use Illuminate\Http\Request;

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->invitationService->delete([$id]);

        return redirect()->route('invitations.index')->with($result['status'], $result['text']);
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
        $result = $this->invitationService->delete($data);
        $request->session()->flash($result['status'], $result['text']);

        return response()->json($result);
    }

    /**
     * Send the invitation to the user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function send($id)
    {
        $result = $this->invitationService->send($id);

        return response()->json($result);
    }
}
