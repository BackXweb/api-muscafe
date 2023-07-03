<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Facility extends Model
{
    use HasApiTokens;

    protected $table = 'facilities';

    protected $fillable = [
        'playlist_id',
        'user_id',
        'name',
        'address',
        'use_any',
        'token',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playlist() {
        return $this->belongsTo(Playlist::class, 'playlist_id');
    }
}
