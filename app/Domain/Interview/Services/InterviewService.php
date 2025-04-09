<?php

namespace App\Domain\Interview\Services;

use App\Domain\CandidateStage\Exceptions\CandidateStageNotFoundException;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\Interview\Exceptions\InterviewerScheduleHasConflictException;
use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interview\Interfaces\InterviewServiceInterface;
use App\Domain\Interview\Models\Interview;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;
use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterviewService implements InterviewServiceInterface
{
    public function __construct(
        private InterviewRepositoryInterface $interviewRepository,
        private CandidateStageRepositoryInterface $candidateStageRepository,
        private InterviewerRepositoryInterface $interviewerRepository,
        private InterviewerServiceInterface $interviewerService
    ) {}

    public function createInterview(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->validateCandidateStageExists($data);
            $this->checkForInterviewerScheduleConflicts($data);

            $interview = $this->createInterviewModel($data);
            $this->interviewRepository->save(interview: $interview);

            $this->assignInterviewersToInterview($data, $interview);
        });
    }


    private function validateCandidateStageExists(array $data): void
    {
        if (!$this->candidateStageRepository->candidateStageExistsByID(id: $data['candidate_stage_id'])) {
            throw new CandidateStageNotFoundException(candidateStageId: $data['candidate_stage_id']);
        }
    }

    private function checkForInterviewerScheduleConflicts(array $data): void
    {
        if (isset($data['interviewers']) && is_array($data['interviewers'])) {
            $scheduledAt = Carbon::parse($data['scheduled_at']);
            $durationMinutes = $data['duration_minutes'];

            foreach ($data['interviewers'] as $interviewerId) {
                $this->validateInterviewerAvailability($interviewerId, $scheduledAt, $durationMinutes);
            }
        }
    }

    private function validateInterviewerAvailability(int $interviewerId, Carbon $scheduledAt, int $durationMinutes): void
    {
        $hasConflict = $this->interviewerRepository->hasScheduleConflict(
            userId: $interviewerId,
            startTime: $scheduledAt,
            durationMinutes: $durationMinutes
        );

        if ($hasConflict) {
            throw new InterviewerScheduleHasConflictException(
                errors: [
                    'user_id' => $interviewerId,
                    'scheduled_at' => $scheduledAt
                ]
            );
        }
    }

    private function createInterviewModel(array $data): Interview
    {
        return Interview::make([
            'candidate_stage_id' => $data['candidate_stage_id'],
            'scheduled_at' => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'],
            'location' => $data['location'] ?? null,
            'meeting_link' => $data['meeting_link'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by' => Auth::id()
        ]);
    }

    private function assignInterviewersToInterview(array $data, Interview $interview): void
    {
        if (isset($data['interviewers']) && is_array($data['interviewers'])) {
            foreach ($data['interviewers'] as $interviewerId) {
                $this->interviewerService->assignInterviewer([
                    'interview_id' => $interview->id,
                    'user_id' => $interviewerId
                ]);
            }
        }
    }
}
