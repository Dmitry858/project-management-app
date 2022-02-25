<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
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
}
