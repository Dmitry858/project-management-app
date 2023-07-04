<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function formattedStart(): string
    {
        return Carbon::parse($this->start)->format('d.m.Y H:i:s');
    }

    public function formattedEnd(): string
    {
        return Carbon::parse($this->end)->format('d.m.Y H:i:s');
    }

    public function type(): string
    {
        $eventType = '';
        if ($this->user_id === auth()->id() && $this->is_private === 1)
        {
            $eventType = __('calendar.private');
        }
        else if ($this->is_private === 0)
        {
            $eventType = $this->project_id === null ? __('calendar.public') : __('calendar.project_unnamed');
        }

        return $eventType;
    }
}
