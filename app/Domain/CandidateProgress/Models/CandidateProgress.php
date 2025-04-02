<?php

namespace App\Domain\CandidateProgress\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateProgress extends Model
{
    protected $table = 'candidate_progresses';
    protected $fillable = [
        'recruitment_batch_id',
        'candidate_id',
        'candidate_stage_id'
    ];
}
