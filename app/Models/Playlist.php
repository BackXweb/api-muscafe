<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $table = 'playlists';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'time_start',
        'time_end',
    ];

    public function playlist_to_style() {
        $this->hasMany(PlaylistToStyle::class, 'playlist_id');
    }

    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }

    public function playlist_to_ad() {
        $this->hasMany(PlaylistToAd::class, 'playlist_id');
    }

    public function facilities() {
        $this->hasMany(Facility::class, 'playlist_id');
    }
}
