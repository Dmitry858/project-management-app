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

    public function get(int $id)
    {
        if (Auth::user()->hasRole('admin'))
        {
            return $this->taskRepository->find($id);
        }
        else
        {
            $currentMember = Auth::user()->member()->first();
            if ($currentMember)
            {
                $task = $this->taskRepository->find($id);
                if ($currentMember->id === $task->owner_id || $currentMember->id === $task->responsible_id)
                {
                    return $task;
                }
            }
        }
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
