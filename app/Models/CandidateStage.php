<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateStage extends Model
{
    protected $fillable = [
        'status',
        'scheduled_at',
        'completed_at',
        'feedback',
        'passed',
        'rejection_reason'
    ];
}
