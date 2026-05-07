<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Grade;
use App\Models\Campus;

class Testimonial extends Model
{
    use SoftDeletes;

    protected $table = 'testimonials';

    protected $fillable = [
        'campus_id',
        'grade_id',
        'student',
        'title',
        'images',
        'content',
        'status',
        'rating',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
