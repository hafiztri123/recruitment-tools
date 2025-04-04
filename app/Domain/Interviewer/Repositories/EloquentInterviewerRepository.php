<?php

namespace App\Domain\Interviewer\Repositories;

use App\Domain\Interview\Models\Interviewer;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;

class EloquentInterviewerRepository implements InterviewerRepositoryInterface
{
    public function save(Interviewer $interviewer): void
    {
        $interviewer->saveOrFail();
    }

    public function findByInterviewIdAndUserId(int $interviewId, int $userId): Interviewer
    {
        return Interviewer::where('interview_id', $interviewId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
