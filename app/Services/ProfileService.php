<?php

namespace App\Services;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function handleData(UpdateProfileRequest $request): array
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['password_confirmation']);
        if (!$data['password'])
        {
            unset($data['password']);
        }
        else
        {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('photo'))
        {
            $data['photo'] = $request->file('photo')->store('users-photo');
        }

        return $data;
    }
}
