<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'campus_id',
        'title',
        'title_id',
        'img',
        'content',
        'content_id',
        'tanggal',
        'promoter',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
