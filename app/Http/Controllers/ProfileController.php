<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfileService;
use App\Models\User;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        $user = Auth::user();
        $title = 'Профиль пользователя';

        return view('profile', compact('title', 'user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $this->profileService->handleData($request);
        $success = User::where('id', Auth::id())->update($data);
        $flashKey = $success ? 'success' : 'error';
        $flashValue = $success ? __('flash.changes_saved') : __('flash.general_error');

        return redirect()->route('profile')->with($flashKey, $flashValue);
    }
}
