<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagline extends Model
{
    protected $fillable = [
        'title',
        'title_id',
        'jargon',
        'jargon_id',
        'content',
        'content_id',
    ];
}
