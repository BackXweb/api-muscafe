<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'use_any',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playlist() {
        return $this->belongsTo(Playlist::class, 'playlist_id');
    }
}
