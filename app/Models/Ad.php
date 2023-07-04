<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    protected $hidden = ['pivot'];

    protected $table = 'ads';

    protected $fillable = [
        'user_id',
        'name',
        'storage'
    ];

    public function getStorageAttribute($value) {
        return Storage::url($value);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playlist_to_ad() {
        return $this->hasMany(PlaylistToAd::class, 'ad_id');
    }

    public function playlist() {
        return $this->belongsToMany(Playlist::class, 'playlist_to_ad')->using(PlaylistToAd::class);
    }
}
