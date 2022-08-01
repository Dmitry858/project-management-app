<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getList(array $filter = [])
    {
        if (Auth::user()->hasRole('admin'))
        {
            return $this->taskRepository->search($filter);
        }
        else
        {
            $member = Auth::user()->member()->first();
            if ($member)
            {
                $filter['is_active'] = 1;
                return $this->taskRepository->searchByMember($member->id, $filter);
            }
            else
            {
                return [];
            }
        }
    }

    public function create($data)
    {
        unset($data['_token']);

        return $this->taskRepository->createFromArray($data);
    }
}
