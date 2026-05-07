<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $fillable = [
        'img', 'campus_id', 'title', 'title_id', 'content', 'content_id', 'deleted_at'
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'id');
    }
}
