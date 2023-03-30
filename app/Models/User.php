<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'photo',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->last_name}";
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role): bool {
        return (bool) $this->roles->where('slug', $role)->count();
    }

    public function hasPermission($permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    public function hasPermissionThroughRole($permission): bool
    {
        $permission = Permission::where('slug', $permission)->first();

        if (!$permission) return false;

        foreach ($permission->roles as $role)
        {
            if ($this->hasRole($role->slug))
            {
                return true;
            }
        }

        return false;
    }

    public function photoSrc(): string
    {
        if ($this->photo && Storage::exists($this->photo))
        {
            $ext = File::extension($this->photo);
            $src = 'data:image/'.$ext.';base64,'.base64_encode(Storage::get($this->photo));
        }
        else
        {
            $src = 'data:image/jpeg;base64,'.base64_encode(Storage::get('no_photo.jpg'));
        }

        return $src;
    }
}
