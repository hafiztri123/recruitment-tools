<?php


namespace App\Domain\Interviewer\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface InterviewerServiceInterface
{
    public function assignInterviewer(array $data): void;
    public function assignInterviewers(array $multipleData): string;
    public function interviewerFillFeedback(array $data): void;
    public function getAvailableInterviewers(Carbon $startTime, int $durationMinutes): Collection;
}


