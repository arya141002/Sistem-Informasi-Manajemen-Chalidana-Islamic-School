<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerApplication extends Model
{
    protected $fillable = [
        'career_id',
        'full_name',
        'email',
        'phone',
        'latest_education',
        'portfolio_url',
        'cv_path',
        'cover_message',
        'status',
        'source_locale',
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}
