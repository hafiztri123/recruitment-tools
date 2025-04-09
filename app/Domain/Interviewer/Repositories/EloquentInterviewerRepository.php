<?php

namespace App\Domain\Interviewer\Repositories;

use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;
use App\Domain\Interviewer\Models\Interviewer;
use Carbon\Carbon;

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

    /**
     * select 1 from interviewer
     * join interview on interviewer.interview_id = interviewer.id
     * where user_id = $userId
     * and (
     *  select 1 from interview
     *  where(
     *  (select 1 from interview where scheduled_at > start_time and date_add(scheduled))
     *  )
     * )
     *
     */

    public function hasScheduleConflict(int $userId, Carbon $startTime, int $durationMinutes, ?int $excludeInterviewId = null): bool
    {
        // Calculate the end time for the new interview
        $endTime = (clone $startTime)->addMinutes($durationMinutes);

        // Get existing interviews for this user that might conflict
        $query = Interviewer::where('user_id', $userId)
            ->whereHas('interview', function ($q) use ($startTime, $endTime, $excludeInterviewId) {
                // The Allen's interval algebra simplified:


                // Existing interview start before the new end time
                $q->where(function ($q) use ($startTime, $endTime) {
                    $q->where('scheduled_at', '<', $endTime)
                        // And existing interview ends after new start time
                        ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE) > ?', [$startTime]);
                });

                // Exclude the current interview when updating
                if ($excludeInterviewId) {
                    $q->where('id', '!=', $excludeInterviewId);
                }
            });

        return $query->exists();
    }
}

