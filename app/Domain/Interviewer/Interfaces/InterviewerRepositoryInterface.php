<?php

namespace App\Domain\Interviewer\Interfaces;

use App\Domain\Interview\Models\Interviewer;

interface InterviewerRepositoryInterface
{
    public function save(Interviewer $interviewer): void;
    public function findByInterviewIdAndUserId(int $interviewId, int $userId): Interviewer;
}
