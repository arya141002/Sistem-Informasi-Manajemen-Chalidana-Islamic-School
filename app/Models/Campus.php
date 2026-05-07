<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'campuses';
    protected $fillable = [
        'catcampus_id',
        'title',
        'NPSN',
        'school_tour',
        'subject',
        'slug',
        'img_header',
        'img_principal',
        'from',
        'from_id',
        'message',
        'message_id',
        'address',
        'maplocation',
        'content',
        'content_id'
    ];

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'campus_id', 'id');
    }

    public function campusArea()
    {
        return $this->belongsTo(CampusArea::class, 'catcampus_id', 'id');
    }
}
