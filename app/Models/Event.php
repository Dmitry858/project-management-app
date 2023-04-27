<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'is_allday',
        'is_private',
        'project_id',
        'user_id',
    ];
}
