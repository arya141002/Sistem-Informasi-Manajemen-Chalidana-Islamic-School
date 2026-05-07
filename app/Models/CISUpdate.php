<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CISUpdate extends Model
{
    protected $table = 'cis_update';
    protected $fillable = [
        'title', 'title_id', 'img', 'status', 'url', 'deleted_at'
    ];
}
