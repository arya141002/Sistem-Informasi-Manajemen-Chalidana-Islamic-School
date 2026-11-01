<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentView extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'ip_hash',
        'viewed_on',
    ];

    protected $casts = [
        'viewed_on' => 'date',
    ];
}
