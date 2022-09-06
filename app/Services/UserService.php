<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get(int $id)
    {
        return $this->userRepository->find($id);
    }

    public function getList(array $filter = [], bool $withPaginate = true)
    {
        return $this->userRepository->search($filter, $withPaginate);
    }

    public function update(int $id, UpdateUserRequest $request): bool
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

        if (Cache::has('user_'.$id.'_roles'))
        {
            Cache::forget('user_'.$id.'_roles');
        }

        if (Cache::has('user_'.$id.'_permissions'))
        {
            Cache::forget('user_'.$id.'_permissions');
        }

        return $this->userRepository->updateFromArray($id, $data);
    }

    public function create($request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('photo'))
        {
            $data['photo'] = $request->file('photo')->store('users-photo');
        }

        return $this->userRepository->createFromArray($data);
    }

    public function delete(int $id): array
    {
        if ($this->userRepository->hasMember($id))
        {
            return [
                'status' => 'error',
                'text' => __('errors.user_is_member')
            ];
        }

        $success = $this->userRepository->delete($id);

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.user_deleted') : __('flash.general_error')
        ];
    }
}
