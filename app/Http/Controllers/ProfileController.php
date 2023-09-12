<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class ProfileController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $user = Auth::user();
        $title = __('titles.profile');

        return view('profile', compact('title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        if (intval($id) !== Auth::id())
        {
            $flashKey = 'error';
            $flashValue = __('errors.user_is_wrong');
        }
        else
        {
            $success = $this->userService->update($id, $request, true);
            $flashKey = $success ? 'success' : 'error';
            $flashValue = $success ? __('flash.changes_saved') : __('flash.general_error');
        }

        return redirect()->route('profile')->with($flashKey, $flashValue);
    }
}
