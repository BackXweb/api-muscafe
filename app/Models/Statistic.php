<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistics';

    protected $fillable = [
        'facility_id',
        'storage_music',
    ];

    public function facility() {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
