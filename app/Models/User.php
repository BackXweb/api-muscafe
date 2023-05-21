<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'login',
        'password',
        'name',
        'bitrix_link',
        'subscribe_end',
    ];

    protected $hidden = ['password'];

    protected $casts = ["last_online_at" => "datetime"];

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
