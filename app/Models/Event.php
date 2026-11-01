<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function views(): HasMany
    {
        return $this->hasMany(ContentView::class, 'content_id')
            ->where('content_type', self::class);
    }
}
