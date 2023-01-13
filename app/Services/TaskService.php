<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskService
{
    protected $taskRepository;
    protected $attachmentRepository;

    public function __construct(TaskRepositoryInterface $taskRepository, AttachmentRepositoryInterface $attachmentRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->attachmentRepository = $attachmentRepository;
    }

    public function get(int $id)
    {
        if (PermissionService::hasUserPermission(Auth::id(), 'view-all-tasks'))
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

    /**
     * @param array $filter
     * @param bool $withPaginate
     * @param array $with
     */
    public function getList(array $filter = [], bool $withPaginate = true, array $with = [])
    {
        if (PermissionService::hasUserPermission(Auth::id(), 'view-all-tasks'))
        {
            return $this->taskRepository->search($filter, $withPaginate, $with);
        }
        else
        {
            $member = Auth::user()->member()->first();
            if ($member)
            {
                $filter['is_active'] = 1;
                return $this->taskRepository->searchByMember($member->id, $filter, $withPaginate, $with);
            }
            else
            {
                return [];
            }
        }
    }

    public function create(Request $request): array
    {
        $data = $request->all();
        unset($data['_token']);

        $task = $this->taskRepository->createFromArray($data);
        if ($task)
        {
            $attachments = [];
            if ($request->hasFile('attachments'))
            {
                foreach ($request->file('attachments') as $file)
                {
                    $filePath = $file->store('attachments');
                    $attachments[] = [
                        'file_name' => $file->getClientOriginalName(),
                        'file' => $filePath,
                        'task_id' => $task->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }

            if (count($attachments) > 0)
            {
                $this->attachmentRepository->createFromArray($attachments);
            }

            return [
                'status' => 'success',
                'text' => __('flash.task_created')
            ];
        }
        else
        {
            return [
                'status' => 'error',
                'text' => __('flash.general_error')
            ];
        }
    }

    public function update(int $id, array $data, bool $onlyStage = false)
    {
        if ($onlyStage)
        {
            if (intval($data['stage_id']) === 0)
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.stage_not_found')
                ];
            }

            if (!$this->canUserChangeStage($id))
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.change_stage_forbidden')
                ];
            }

            $success = $this->taskRepository->updateFromArray($id, $data);

            if ($success)
            {
                return [
                    'status' => 'success',
                    'text' => __('success_messages.stage_changed')
                ];
            }
            else
            {
                return [
                    'status' => 'error',
                    'text' => __('errors.general')
                ];
            }
        }
        else
        {
            return $this->taskRepository->updateFromArray($id, $data);
        }
    }

    public function delete(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function canUserChangeStage($taskId): bool
    {
        $result = false;

        $task = $this->taskRepository->find($taskId);
        $currentMember = Auth::user()->member;

        if ($task && $currentMember)
        {
            if ($task->owner_id === $currentMember->id || $task->responsible_id === $currentMember->id)
            {
                $result = true;
            }
        }

        return $result;
    }
}
