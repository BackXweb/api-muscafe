<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $hidden = ['pivot'];

    protected $table = 'playlists';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'time_start',
        'time_end',
    ];

    public function playlist_to_style() {
        return $this->hasMany(PlaylistToStyle::class, 'playlist_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ads() {
        return $this->belongsToMany(Ad::class, 'playlist_to_ad')->using(PlaylistToAd::class);
    }

    public function playlist_to_ad() {
        return $this->hasMany(PlaylistToAd::class, 'playlist_id');
    }

    public function facilities() {
        return $this->hasMany(Facility::class, 'playlist_id');
    }
}
