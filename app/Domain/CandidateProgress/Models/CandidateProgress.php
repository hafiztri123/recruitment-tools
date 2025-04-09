<?php

namespace App\Domain\CandidateProgress\Models;

use App\Domain\Candidate\Models\Candidate;
use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use Illuminate\Database\Eloquent\Model;

class CandidateProgress extends Model
{
    protected $table = 'candidate_progresses';
    protected $fillable = [
        'recruitment_batch_id',
        'candidate_id',
        'candidate_stage_id'
    ];

    public function recruitmentBatch()
    {
        return $this->belongsTo(RecruitmentBatch::class, 'recruitment_batch_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function candidateStage()
    {
        return $this->belongsTo(CandidateStage::class, 'candidate_stage_id');
    }



}
