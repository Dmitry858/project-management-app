<?php

namespace App\Services;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;
use Carbon\Carbon;

class CommentService
{
    protected $commentRepository;
    protected $taskRepository;

    public function __construct(CommentRepositoryInterface $commentRepository, TaskRepositoryInterface $taskRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->taskRepository = $taskRepository;
    }

    public function create($data)
    {
        $commentText = htmlspecialchars(trim($data['comment']));
        if (!$commentText)
        {
            return [
                'status' => 'error',
                'text' => __('errors.comment_is_empty')
            ];
        }

        $user = Auth::user();
        $member = $user->member;

        if (!$member)
        {
            return [
                'status' => 'error',
                'text' => __('errors.member_not_found')
            ];
        }

        $taskId = intval($data['id']);

        if ($taskId === 0)
        {
            return [
                'status' => 'error',
                'text' => __('errors.task_id_not_passed')
            ];
        }

        $task = $this->taskRepository->find($taskId);

        if (!$task)
        {
            return [
                'status' => 'error',
                'text' => __('errors.task_not_found')
            ];
        }

        if ($member->id !== $task->owner_id
            && $member->id !== $task->responsible_id
            && !PermissionService::hasUserPermission($user->id, 'add-comments'))
        {
            return [
                'status' => 'error',
                'text' => __('errors.no_permission')
            ];
        }

        $result = $this->commentRepository->createFromArray([
            'comment_text' => $commentText,
            'task_id' => $task->id,
            'member_id' => $member->id
        ]);

        if ($result)
        {
            $result['full_name'] = $user->name;
            if ($user->last_name) $result['full_name'] .= ' ' . $user->last_name;
            $result['datetime'] = Carbon::parse($result['created_at'])->format('d.m.Y H:i:s');
            $result['editable'] = PermissionService::hasUserPermission($user->id, 'edit-comments');
            $result['deletable'] = PermissionService::hasUserPermission($user->id, 'delete-comments');

            return [
                'status' => 'success',
                'result' => $result
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

    public function delete($id)
    {
        if (!PermissionService::hasUserPermission(Auth::id(), 'delete-comments'))
        {
            return [
                'status' => 'error',
                'text' => __('errors.no_permission_to_delete_comment')
            ];
        }

        $comment = $this->commentRepository->find(intval($id));
        if (!$comment)
        {
            return [
                'status' => 'error',
                'text' => __('errors.comment_not_found')
            ];
        }

        $success = $this->commentRepository->delete($comment->id);

        if ($success)
        {
            return [
                'status' => 'success'
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

    public function update(int $id, array $data): array
    {
        if (!isset($data['comment_text']) || trim($data['comment_text']) === '')
        {
            return [
                'status' => 'error',
                'text' => __('errors.comment_is_empty')
            ];
        }

        if (!PermissionService::hasUserPermission(Auth::id(), 'edit-comments'))
        {
            return [
                'status' => 'error',
                'text' => __('errors.no_permission_to_edit_comment')
            ];
        }

        $success = $this->commentRepository->updateFromArray($id, $data);

        if ($success)
        {
            return [
                'status' => 'success'
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
}
