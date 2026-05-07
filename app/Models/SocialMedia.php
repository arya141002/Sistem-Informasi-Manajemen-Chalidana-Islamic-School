<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'sosial_media';

    protected $fillable = [
        'title',
        'link',
        'icon',
        'bgcolor'
    ];
}
