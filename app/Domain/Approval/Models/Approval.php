<?php

namespace App\Domain\Approval\Models;

use App\Domain\Candidate\Models\Candidate;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'candidate_id',
        'approver_id',
        'status',
        'comments',
        'approved_at'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
