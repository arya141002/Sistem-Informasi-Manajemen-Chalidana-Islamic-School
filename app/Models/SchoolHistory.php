<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolHistory extends Model
{
    protected $table = 'school_history';
    protected $fillable = ['title', 'title_id', 'content', 'content_id'];
}
