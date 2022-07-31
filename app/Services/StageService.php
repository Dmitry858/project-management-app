<?php

namespace App\Services;

use App\Repositories\Interfaces\StageRepositoryInterface;

class StageService
{
    protected $stageRepository;

    public function __construct(StageRepositoryInterface $stageRepository)
    {
        $this->stageRepository = $stageRepository;
    }

    public function getList(array $filter = [])
    {
        return $this->stageRepository->search($filter);
    }
}
