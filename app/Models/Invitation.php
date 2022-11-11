<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'secret_key',
        'email',
        'is_sent',
    ];

    public function isExpired(): bool
    {
        return $this->created_at->diffInHours(Carbon::now()) >= 24;
    }
}
