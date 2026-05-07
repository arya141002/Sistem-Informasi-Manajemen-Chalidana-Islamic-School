<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhyChoose extends Model
{
    protected $table = 'why_choose';

    protected $fillable = [
        'title',
        'title_id',
        'content',
        'content_id',
    ];
}
