<?php

namespace App\Domain\Interviewer\Interfaces;

use App\Domain\Interview\Models\Interviewer;
use Carbon\Carbon;

interface InterviewerRepositoryInterface
{
    public function save(Interviewer $interviewer): void;
    public function findByInterviewIdAndUserId(int $interviewId, int $userId): Interviewer;
    public function hasScheduleConflict(int $userId, Carbon $startTime, int $durationMinutes, ?int $excludeInterviewID = null): bool;

}
