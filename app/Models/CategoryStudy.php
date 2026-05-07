<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryStudy extends Model
{
    use SoftDeletes;

    protected $table = 'category_studies';

    protected $fillable = [
        'title',
        'slug',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'study_id');
    }
}
