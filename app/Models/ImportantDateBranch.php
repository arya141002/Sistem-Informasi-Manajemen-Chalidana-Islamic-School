<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDateBranch extends Model
{
    protected $table = 'important_date_branches';

    protected $fillable = [
        'name',
        'program',
        'sort_order',
    ];

    public function events()
    {
        return $this->hasMany(ImportantDateEvent::class, 'branch_id');
    }
}
