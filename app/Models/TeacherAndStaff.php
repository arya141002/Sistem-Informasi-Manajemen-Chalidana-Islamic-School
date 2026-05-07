<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAndStaff extends Model
{
    protected $table = 'teacher_and_staff';
    protected $fillable = [
        'img', 'campus_id', 'role', 'content', 'deleted_at'
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'id');
    }
}
