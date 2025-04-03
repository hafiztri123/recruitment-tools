<?php

namespace App\Domain\Interviewer\Services;

use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interview\Models\Interviewer;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;
use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use App\Domain\Interviewer\Jobs\AssignUserAsInterviewer;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;

class InterviewerService implements InterviewerServiceInterface
{
    public function __construct(
        private InterviewerRepositoryInterface $interviewerRepository,
        private UserRepositoryInterface $userRepository,
        private InterviewRepositoryInterface $interviewRepository
    )
    {

    }
    public function assignInterviewer(array $data): void
    {
        if(!$this->interviewRepository->existsById(id: $data['interview_id'])){
            throw new ModelNotFoundException('Interview not found', 404);
        }

        if(!$this->userRepository->existsById(id: $data['user_id'])){
            throw new ModelNotFoundException('User not found', 404);
        }

        $interviewer = Interviewer::make([
            'interview_id' => $data['interview_id'],
            'user_id' => $data['user_id'],
            'feedback_submitted' => $data['feedback_submitted'] ?? null,
            'feedback' => $data['feedback'] ?? null,
            'rating' => $data['rating'] ?? null
        ]);

        $this->interviewerRepository->save(interviewer: $interviewer);
    }

    public function assignInterviewers(array $multipleData): string
    {
        $interviewersID = $multipleData['interviewers'];
        $interviewID = $multipleData['interview_id'];
        $jobs = collect($interviewersID)->map(function($interviewerID) use ($interviewID){
            return new AssignUserAsInterviewer(
                interviewerID: $interviewerID,
                interviewID: $interviewID
            );
        })->toArray();

        $assignMultipleUsersAsInterviewerJob = Bus::batch($jobs)->allowFailures(false)->dispatch();

        return $assignMultipleUsersAsInterviewerJob->id;
    }
}
