<?php

namespace App\Domain\Interviewer\Services;

use App\Domain\Interview\Exceptions\InterviewerScheduleHasConflictException;
use App\Domain\Interview\Exceptions\InterviewNotFoundException;
use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;
use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use App\Domain\Interviewer\Jobs\AssignUserAsInterviewer;
use App\Domain\Interviewer\Models\Interviewer;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class InterviewerService implements InterviewerServiceInterface
{
    public function __construct(
        private InterviewerRepositoryInterface $interviewerRepository,
        private UserRepositoryInterface $userRepository,
        private InterviewRepositoryInterface $interviewRepository
    ) {}

    public function assignInterviewer(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->validateInterviewAndUserExist($data);
            $interview = $this->getInterviewDetails($data['interview_id']);
            $this->validateInterviewerAvailability($data['user_id'], $interview);

            $interviewer = $this->createInterviewerModel($data);
            $this->interviewerRepository->save(interviewer: $interviewer);
        });
    }

    public function assignInterviewers(array $multipleData): string
    {
        $this->validateInterviewExists($multipleData['interview_id']);

        $interviewersID = $multipleData['interviewers'];
        $interviewID = $multipleData['interview_id'];

        $batch = $this->createBatchForInterviewers($interviewID);
        $this->addInterviewerJobsToBatch($batch, $interviewersID, $interviewID);

        return $batch->id;
    }

    public function interviewerFillFeedback(array $data): void
    {
        DB::transaction(function () use ($data) {
            $interviewerId = Auth::id();
            $interviewId = $data['interview_id'];

            $interviewer = $this->interviewerRepository->findByInterviewIdAndUserId(
                interviewId: $interviewId,
                userId: $interviewerId
            );

            $this->updateInterviewerFeedback($interviewer, $data);
            $this->interviewerRepository->save($interviewer);
        });
    }

    public function getAvailableInterviewers(Carbon $startTime, int $durationMinutes): Collection
    {
        $potentialInterviewers = $this->userRepository->getAllPotentialInterviewers();
        return $this->filterAvailableInterviewers($potentialInterviewers, $startTime, $durationMinutes);
    }

    // Private helper methods

    private function validateInterviewAndUserExist(array $data): void
    {
        if (!$this->interviewRepository->existsById(id: $data['interview_id'])) {
            throw new InterviewNotFoundException(interviewId: $data['interview_id']);
        }

        if (!$this->userRepository->existsById(id: $data['user_id'])) {
            throw new UserNotFoundException(userId: $data['user_id']);
        }
    }

    private function getInterviewDetails(int $interviewId): object
    {
        return $this->interviewRepository->findByIdWithDetails(id: $interviewId);
    }

    private function validateInterviewerAvailability(int $userId, object $interview): void
    {
        $hasConflict = $this->interviewerRepository->hasScheduleConflict(
            userId: $userId,
            startTime: Carbon::parse($interview->scheduled_at),
            durationMinutes: $interview->duration_minutes
        );

        if ($hasConflict) {
            throw new InterviewerScheduleHasConflictException(
                errors: [
                    [
                        'user_id' => $userId,
                        'scheduled_at' => $interview->scheduled_at
                    ]
                ]
            );
        }
    }

    private function createInterviewerModel(array $data): Interviewer
    {
        return Interviewer::make([
            'interview_id' => $data['interview_id'],
            'user_id' => $data['user_id'],
            'feedback_submitted' => $data['feedback_submitted'] ?? null,
            'feedback' => $data['feedback'] ?? null,
            'rating' => $data['rating'] ?? null
        ]);
    }

    private function validateInterviewExists(int $interviewID): void
    {
        DB::transaction(function () use ($interviewID) {
            if (!$this->interviewRepository->existsById($interviewID)) {
                throw new InterviewNotFoundException(interviewId: $interviewID);
            }
        });
    }

    private function createBatchForInterviewers(int $interviewID): object
    {
        return Bus::batch([])
            ->allowFailures(false)
            ->name('Assign interviewers to interview ' . $interviewID)
            ->dispatch();
    }

    private function addInterviewerJobsToBatch(object $batch, array $interviewersID, int $interviewID): void
    {
        $chunkSize = 10;

        collect($interviewersID)->chunk($chunkSize)->each(function ($interviewerChunk) use ($batch, $interviewID) {
            $jobs = $interviewerChunk->map(function ($interviewerID) use ($interviewID) {
                return new AssignUserAsInterviewer(
                    interviewerID: $interviewerID,
                    interviewID: $interviewID
                );
            })->toArray();

            $batch->add($jobs);
        });
    }

    private function updateInterviewerFeedback(Interviewer $interviewer, array $data): void
    {
        if (isset($data['feedback'])) {
            $interviewer->feedback = $data['feedback'];
        }

        if (isset($data['rating'])) {
            $interviewer->rating = $data['rating'];
        }

        if (isset($data['feedback']) || isset($data['rating'])) {
            $interviewer->feedback_submitted = true;
        }
    }

    private function filterAvailableInterviewers(Collection $potentialInterviewers, Carbon $startTime, int $durationMinutes): Collection
    {
        return $potentialInterviewers->filter(function ($user) use ($startTime, $durationMinutes) {
            return !$this->interviewerRepository->hasScheduleConflict(
                userId: $user->id,
                startTime: $startTime,
                durationMinutes: $durationMinutes
            );
        });
    }
}
