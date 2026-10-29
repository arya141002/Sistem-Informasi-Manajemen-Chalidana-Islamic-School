<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'images';
    protected $fillable = [
        'img_id',
        'img',
        'title',
        'title_id',
    ];

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'img_id', 'id');
    }
    
    
}
