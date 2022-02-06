<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function handleData(array $data): array
    {
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

        return $data;
    }
}
