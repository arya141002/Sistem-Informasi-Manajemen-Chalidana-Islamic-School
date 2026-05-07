<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YoutubeShortLink extends Model
{
    protected $table = 'youtube_shorts';

    protected $fillable = [
        'campus_id',
        'title',
        'youtube_short',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
