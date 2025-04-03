<?php

namespace App\Domain\Interview\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'candidate_stage_id',
        'scheduled_at',
        'duration_minutes',
        'location',
        'meeting_link',
        'notes',
        'created_by'
    ];
};
