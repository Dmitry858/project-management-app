<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getList(array $filter = [])
    {
        return $this->projectRepository->search($filter);
    }
}
