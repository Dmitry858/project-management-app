<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use Intervention\Image\Facades\Image;

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

    public function update(int $id, UpdateUserRequest $request, bool $isProfile = false): bool
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
            $user = $this->userRepository->find($id);
            if (!$user) return false;
            if ($user->photo)
            {
                unlink(storage_path('app/'.$user->photo));
            }
            $data['photo'] = $this->resizeAndSavePhoto($request);
        }

        if (Cache::has('user_'.$id.'_roles'))
        {
            Cache::forget('user_'.$id.'_roles');
        }

        if (Cache::has('user_'.$id.'_permissions'))
        {
            Cache::forget('user_'.$id.'_permissions');
        }

        return $this->userRepository->updateFromArray($id, $data, $isProfile);
    }

    public function create($request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('photo'))
        {
            $data['photo'] = $this->resizeAndSavePhoto($request);
        }

        return $this->userRepository->createFromArray($data);
    }

    public function delete(array $ids): array
    {
        if (isset($ids['ids']) && is_array($ids['ids'])) $ids = $ids['ids'];

        $users = $this->userRepository->search(['id' => $ids], false);

        if (count($users) === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.user_not_found')
            ];
        }

        foreach ($users as $user)
        {
            if ($this->userRepository->hasMember($user->id))
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.user_is_member')
                ];
            }
        }

        $success = $this->userRepository->delete($ids);

        $successMsg = count($ids) > 1 ? __('flash.users_deleted') : __('flash.user_deleted');

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? $successMsg : __('flash.general_error')
        ];
    }

    public function resizeAndSavePhoto($request): string
    {
        $photo = $request->file('photo');
        $fileName = $photo->getClientOriginalName();
        $resizedPhoto = Image::make($photo->path());
        $resizedPhoto->resize(250, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        if (!file_exists(storage_path('app/users-photo')))
        {
            mkdir(storage_path('app/users-photo'), 0755);
        }
        $resizedPhoto->save(storage_path('app/users-photo/'.$fileName));

        return 'users-photo/'.$fileName;
    }
}
