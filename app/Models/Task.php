<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\EloquentMemberRepository;
use Illuminate\Support\Facades\Cache;

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

    public function getMemberFullName(int $memberId): string
    {
        $fullName = '';
        if (Cache::has('member_'.$memberId.'_fullname'))
        {
            $fullName = Cache::get('member_'.$memberId.'_fullname');
        }
        else
        {
            $repo = new EloquentMemberRepository;
            $member = $repo->find($memberId);
            if ($member)
            {
                $fullName = $member->user->name.' '.$member->user->last_name;
                Cache::put('member_'.$memberId.'_fullname', $fullName, 86400);
            }
        }

        return trim($fullName);
    }
}
