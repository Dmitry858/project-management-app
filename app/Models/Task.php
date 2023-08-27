<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MemberService;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'responsible_id',
        'stage_id',
        'project_id',
        'deadline',
        'is_active',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function owner()
    {
        return $this->belongsTo(Member::class, 'owner_id');
    }

    public function responsible()
    {
        return $this->belongsTo(Member::class, 'responsible_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getMemberFullName(int $memberId): string
    {
        return MemberService::getMemberFullName($memberId);
    }

    public function formattedDeadline(): string|null
    {
        $value = $this->deadline;
        if ($value)
        {
            $value = date('d.m.Y H:i', strtotime($value));
        }

        return $value;
    }
}
