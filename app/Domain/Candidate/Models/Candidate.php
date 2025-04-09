<?php

namespace App\Domain\Candidate\Models;

use App\Domain\CandidateProgress\Models\CandidateProgress;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'whatsapp',
        'resume_path',
        'source',
        'status',
        'notes',
    ];

    public function candidateProgresses()
    {
        return $this->hasMany(CandidateProgress::class, 'candidate_id');
    }
}
