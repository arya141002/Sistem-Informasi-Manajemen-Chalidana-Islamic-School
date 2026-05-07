<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'study_id',
        'img',
        'tanggal',
        'slug',
        'title',
        'title_id',
        'content',
        'content_id',
    ];

    public function study()
    {
        return $this->belongsTo(CategoryStudy::class, 'study_id');
    }
}
