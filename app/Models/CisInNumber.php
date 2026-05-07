<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CisInNumber extends Model
{
    use SoftDeletes;

    protected $table = '_cis_in_number';

    protected $fillable = [
        'icon',
        'title',
        'title_id',
        'number',
        'content',
    ];
}
