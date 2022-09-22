<?php

namespace App\Models;

use App\Services\MemberService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function getFullName(): string
    {
        return MemberService::getMemberFullName($this->id);
    }
}
