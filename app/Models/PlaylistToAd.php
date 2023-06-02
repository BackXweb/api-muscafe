<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistToAd extends Model
{
    protected $table = 'playlist_to_ad';

    protected $fillable = [
        'ad_id',
        'playlist_id',
        'time',
        'use_any'
    ];

    public function playlist() {
        $this->belongsTo(Playlist::class, 'playlist_id');
    }

    public function ad() {
        $this->belongsTo(Ad::class, 'ad_id');
    }
}
