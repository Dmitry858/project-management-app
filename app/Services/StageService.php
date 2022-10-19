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

    public function update(int $id, array $data): array
    {
        $success = $this->stageRepository->updateFromArray($id, $data);

        if ($success && Cache::has('all_stages'))
        {
            Cache::forget('all_stages');
        }

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.stage_updated') : __('flash.general_error')
        ];
    }

    public function delete(int $id): array
    {
        $stage = $this->stageRepository->find($id);

        if ($stage && count($stage->tasks) > 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.stage_is_used')
            ];
        }

        if ($this->stageRepository->getCount() === 1)
        {
            return [
                'status' => 'error',
                'text' => __('errors.stage_is_last')
            ];
        }

        $success = $this->stageRepository->delete($id);

        if ($success && Cache::has('all_stages'))
        {
            Cache::forget('all_stages');
        }

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? __('flash.stage_deleted') : __('flash.general_error')
        ];
    }
}
