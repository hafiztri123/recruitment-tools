<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function recruitmentStage(): BelongsTo
    {
        return $this->belongsTo(RecruitmentStage::class, 'stage_id', 'id');


    }
}
