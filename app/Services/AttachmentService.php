<?php

namespace App\Services;

use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentService
{
    protected $attachmentRepository;

    public function __construct(AttachmentRepositoryInterface $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function getPath(int $id)
    {
        $path = null;
        $attachment = $this->attachmentRepository->find($id);
        if ($attachment)
        {
            $memberId = Auth::user()->member->id;
            $task = $attachment->task;
            if ($memberId === $task->owner_id || $memberId === $task->responsible_id || PermissionService::hasUserPermission(Auth::id(), 'view-all-tasks'))
            {
                $path = Storage::path($attachment->file);
            }
        }

        return $path;
    }
}
