<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistToStyle extends Model
{
    protected $table = 'playlist_to_style';

    protected $fillable = [
        'playlist_id',
        'storage_style',
        'chance',
        'time'
    ];
}
