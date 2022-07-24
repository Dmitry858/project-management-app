<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
