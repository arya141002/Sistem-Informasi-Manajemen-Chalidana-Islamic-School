<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vision_Mission extends Model
{
    protected $table = 'vision_mission';

    protected $fillable = [
        'title',
        'title_id',
        'content',
        'content_id',
    ];
}
