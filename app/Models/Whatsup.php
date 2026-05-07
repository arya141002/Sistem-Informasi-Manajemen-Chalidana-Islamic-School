<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
