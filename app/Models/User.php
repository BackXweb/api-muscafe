<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'manager_id',
        'role_id',
        'login',
        'password',
        'name',
        'bitrix_link',
        'subscribe_end',
        'status',
        'reset_token',
        'token'
    ];

    protected $hidden = ['password'];

    protected $casts = ["last_online_at" => "datetime"];

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users() {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function facilities() {
        return $this->hasMany(Facility::class, 'user_id');
    }

    public function ads() {
        return $this->hasMany(Ad::class, 'user_id');
    }

    public function playlists() {
        return $this->hasMany(Playlist::class, 'user_id');
    }
}
