<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class About_school extends Model
{
    use SoftDeletes;

    protected $table = 'about_schools';
    protected $fillable = ['title', 'title_id', 'content', 'content_id'];
}
