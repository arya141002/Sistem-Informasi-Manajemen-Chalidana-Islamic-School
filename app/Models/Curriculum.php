<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curriculums';
    protected $fillable = [
        'title',
        'title_id',
        'content',
        'content_id',
    ];
}
