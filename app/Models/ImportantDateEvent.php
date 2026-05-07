<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDateEvent extends Model
{
    protected $table = 'important_date_events';

    protected $fillable = [
        'branch_id',
        'campus_id',
        'event_date',
        'start_date',
        'end_date',
        'title',
        'description',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(ImportantDateBranch::class, 'branch_id');
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }
}
