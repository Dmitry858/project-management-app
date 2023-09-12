<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\InvitationService;
use App\Services\UserService;

class RegisteredUserController extends Controller
{
    protected InvitationService $invitationService;
    protected UserService $userService;

    public function __construct(InvitationService $invitationService, UserService $userService)
    {
        $this->invitationService = $invitationService;
        $this->userService = $userService;
    }

    /**
     * Display the registration view.
     *
     * @param string $key
     * @return \Illuminate\View\View
     */
    public function create(string $key)
    {
        $invitation = $this->invitationService->getValidInvitationByKey($key);
        if ($invitation)
        {
            return view('auth.register', ['key' => $key, 'email' => $invitation->email]);
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @param StoreUserRequest $request
     * @param string $key
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUserRequest $request, string $key)
    {
        $invitation = $this->invitationService->getValidInvitationByKey($key);
        if (!$invitation) abort(404);

        $user = $this->userService->create($request);

        if ($user)
        {
            $invitation->update(['user_id' => $user->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
