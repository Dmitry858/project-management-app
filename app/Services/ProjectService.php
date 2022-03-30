<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    protected $projectRepository;
    protected $userRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository, UserRepositoryInterface $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    public function get(int $id)
    {
        if (Auth::user()->hasRole('admin'))
        {
            return $this->projectRepository->find($id);
        }
        else
        {
            $currentMember = Auth::user()->member()->first();
            if ($currentMember)
            {
                $project = $this->projectRepository->find($id);
                foreach ($project->members as $member)
                {
                    if ($currentMember->id === $member->id && $project->is_active === 1)
                    {
                        return $project;
                    }
                }
            }
        }
    }

    public function getList(array $filter = [])
    {
        if (Auth::user()->hasRole('admin'))
        {
            return $this->projectRepository->search($filter);
        }
        else
        {
            $member = Auth::user()->member()->first();
            if ($member)
            {
                $filter['is_active'] = 1;
                return $this->projectRepository->searchByMember($member->id, $filter);
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

        if (array_key_exists('members', $data) && is_array($data['members']))
        {
            $userIds = $data['members'];
            unset($data['members']);
        }

        $project = $this->projectRepository->createFromArray($data);

        if ($project && isset($userIds) && is_array($userIds))
        {
            foreach ($userIds as $id)
            {
                $user = $this->userRepository->find($id);
                $member = $this->userRepository->findOrCreateMember($user);
                $memberIds[] = $member->id;
            }

            $this->projectRepository->attachMembers($project, $memberIds);
        }

        return $project;
    }
}
