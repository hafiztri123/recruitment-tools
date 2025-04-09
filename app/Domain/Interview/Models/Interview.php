<?php

namespace App\Domain\Interview\Models;

use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\Interviewer\Models\Interviewer;
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

    public function candidateStage()
    {
        return $this->belongsTo(CandidateStage::class, 'candidate_stage_id');
    }

    public function interviewers()
    {
        return $this->hasMany(Interviewer::class, 'interview_id');
    }
};
