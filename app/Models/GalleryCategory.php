<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryCategory extends Model
{
    protected $table = 'studies';
    protected $fillable = [
        'study_id',
        'campus_id',
        'title',
        'title_id',
        'slug',
        'img',
        'location',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'img_id', 'id');
    }

    public function latestGallery()
    {
        return $this->hasOne(Gallery::class, 'img_id', 'id')->latestOfMany();
    }

    public function views(): HasMany
    {
        return $this->hasMany(ContentView::class, 'content_id')
            ->where('content_type', self::class);
    }
}