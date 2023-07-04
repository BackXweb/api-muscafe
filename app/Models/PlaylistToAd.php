<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistToAd extends Pivot
{
    protected $hidden = ['pivot'];

    protected $table = 'playlist_to_ad';

    protected $fillable = [
        'ad_id',
        'playlist_id',
        'time',
        'use_any'
    ];

    public function playlist() {
        return $this->belongsTo(Playlist::class, 'playlist_id');
    }

    public function ad() {
        return $this->belongsTo(Ad::class, 'ad_id');
    }
}
