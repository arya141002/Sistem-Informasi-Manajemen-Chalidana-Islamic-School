<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    protected $table = 'studies';
    protected $fillable = [
        'study_id',
        'title',
        'title_id',
        'slug',
        'img',
        'location',
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'img_id', 'id');
    }
}
