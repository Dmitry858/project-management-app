<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MemberService;
use App\Services\PermissionService;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_text',
        'task_id',
        'member_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getMemberFullName(int $memberId): string
    {
        return MemberService::getMemberFullName($memberId);
    }

    public function isEditable(): bool
    {
        return PermissionService::hasUserPermission(auth()->id(), 'edit-comments');
    }

    public function isDeletable(): bool
    {
        return PermissionService::hasUserPermission(auth()->id(), 'delete-comments');
    }
}
