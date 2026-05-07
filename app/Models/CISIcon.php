<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CISIcon extends Model
{
    protected $table = 'cis_icon';
    protected $fillable = [
      'title', 'file', 'deleted_at'  
    ];
}
