<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusArea extends Model
{
    protected $table = 'campus_area';
    protected $fillable = [
        'title',
        'slug',
        'img',
        'pagehead',
        'phone',
        'address',
        'address_id',
    ];

    public function campuses()
    {
        return $this->hasMany(Campus::class, 'catcampus_id', 'id');
    }
}
