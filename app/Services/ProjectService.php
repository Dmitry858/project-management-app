<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class ProjectService
{
    protected $projectRepository;
    protected $userRepository;
    protected $memberRepository;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        UserRepositoryInterface $userRepository,
        MemberRepositoryInterface $memberRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
    }

    public function get(int $id)
    {
        if (PermissionService::hasUserPermission(Auth::id(), 'view-all-projects'))
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

    public function getList(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        if (PermissionService::hasUserPermission(Auth::id(), 'view-all-projects'))
        {
            return $this->projectRepository->search($filter, $withPaginate, $with);
        }
        else
        {
            $member = Auth::user()->member()->first();
            if ($member)
            {
                $filter['is_active'] = 1;
                return $this->projectRepository->searchByMember($member->id, $filter, $withPaginate, $with);
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

    public function getProjectMembers(object $project)
    {
        $memberIds = [];
        $members = [];
        foreach ($project->members as $member)
        {
            $memberIds[] = $member->user_id;
        }

        if (count($memberIds) > 0)
        {
            $members = $this->userRepository->search(['is_active' => 1, 'id' => $memberIds], false);
        }

        return $members;
    }

    public function update(int $id, array $data): bool
    {
        $memberIds = [];
        if (array_key_exists('members', $data) && is_array($data['members']))
        {
            $members = $this->memberRepository->search(['user_id' => $data['members']], false);
            if (count($members) > 0)
            {
                $notFoundMembers = $data['members'];
                foreach ($members as $member)
                {
                    $memberIds[] = $member->id;
                    $key = array_search($member->user_id, $notFoundMembers);
                    if ($key !== false)
                    {
                        unset($notFoundMembers[$key]);
                    }
                }
            }
            unset($data['members']);
        }
        $data['memberIds'] = $memberIds;

        if (isset($notFoundMembers) && count($notFoundMembers) > 0)
        {
            foreach ($notFoundMembers as $id)
            {
                $newMember = $this->memberRepository->createFromArray(['user_id' => $id]);
                if ($newMember)
                {
                    $data['memberIds'][] = $newMember->id;
                }
            }
        }

        return $this->projectRepository->updateFromArray($id, $data);
    }

    public function delete(int $id): array
    {
        $project = $this->projectRepository->find($id);

        if ($project && count($project->tasks) > 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.project_contains_tasks')
            ];
        }

        if ($project && count($project->members) > 0)
        {
            $this->projectRepository->detachAllMembers($project);
        }

        $success = $this->projectRepository->delete($id);

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.project_deleted') : __('flash.general_error')
        ];
    }
}
