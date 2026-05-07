<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $table = 'careers';

    protected $fillable = [
        'tanggal',
        'career_status',
        'title',
        'title_id',
        'content',
        'content_id',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }
}
