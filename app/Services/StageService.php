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

    public function delete(array $ids): array
    {
        if (isset($ids['ids']) && is_array($ids['ids'])) $ids = $ids['ids'];

        $stages = $this->stageRepository->search(['id' => $ids]);

        if (count($stages) === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.stage_not_found')
            ];
        }

        foreach ($stages as $stage)
        {
            if (count($stage->tasks) > 0)
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.stage_is_used')
                ];
            }
        }

        if ($this->stageRepository->getCount() === count($ids))
        {
            return [
                'status' => 'error',
                'text' => __('errors.stage_is_last')
            ];
        }

        $success = $this->stageRepository->delete($ids);

        if ($success && Cache::has('all_stages'))
        {
            Cache::forget('all_stages');
        }

        $successMsg = count($ids) > 1 ? __('flash.stages_deleted') : __('flash.stage_deleted');

        return [
            'status' => $success ? 'success' : 'error',
            'text' => $success ? $successMsg : __('flash.general_error')
        ];
    }
}
