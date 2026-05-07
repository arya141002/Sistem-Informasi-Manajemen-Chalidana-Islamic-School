<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsupCategory extends Model
{
    protected $table = 'whatsup_category';
    protected $fillable = [
        'slug', 'title', 'title_id', 'deleted_at'
    ];

    public function whatsups()
    {
        return $this->hasMany(Whatsup::class, 'news_id', 'id');
    }
}
