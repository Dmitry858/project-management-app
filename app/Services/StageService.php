<?php

namespace App\Services;

use App\Repositories\Interfaces\StageRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class StageService
{
    protected $stageRepository;

    public function __construct(StageRepositoryInterface $stageRepository)
    {
        $this->stageRepository = $stageRepository;
    }

    public function get(int $id)
    {
        return $this->stageRepository->find($id);
    }

    public function getList(array $filter = [])
    {
        return $this->stageRepository->search($filter);
    }

    public function create($data)
    {
        unset($data['_token']);

        $success = $this->stageRepository->createFromArray($data);

        if ($success && Cache::has('all_stages'))
        {
            Cache::forget('all_stages');
        }

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.stage_created') : __('flash.general_error')
        ];
    }
}
