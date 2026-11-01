<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Whatsup extends Model
{
    protected $table = 'news';
    protected $fillable = [
        'news_id',
        'tanggal',
        'slug',
        'img',
        'title',
        'title_id',
        'content',
        'content_id',
    ];

    public function category()
    {
        return $this->belongsTo(WhatsupCategory::class, 'news_id', 'id');
    }

    public function views(): HasMany
    {
        return $this->hasMany(ContentView::class, 'content_id')
            ->where('content_type', self::class);
    }
}
