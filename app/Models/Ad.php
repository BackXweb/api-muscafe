<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = 'ads';

    protected $fillable = [
        'user_id',
        'name',
        'storage'
    ];

    public function user() {
        $this->belongsTo(User::class, 'user_id');
    }

    public function playlist_to_ad() {
        $this->hasMany(PlaylistToAd::class, 'ad_id');
    }
}
