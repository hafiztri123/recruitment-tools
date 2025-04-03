<?php

namespace App\Domain\Interview\Services;

use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interview\Interfaces\InterviewServiceInterface;
use App\Domain\Interview\Models\Interview;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class InterviewService implements InterviewServiceInterface
{
    public function __construct(
        private InterviewRepositoryInterface $interviewRepository,
        private CandidateStageRepositoryInterface $candidateStageRepository
    ) {}

    public function createInterview(array $data): void
    {
        if(!$this->candidateStageRepository->candidateStageExistsByID(id: $data['candidate_stage_id'])){
            throw new ModelNotFoundException('Candidate stage not found', 404);
        }

        $interview = Interview::make([
            'candidate_stage_id' => $data['candidate_stage_id'],
            'scheduled_at' => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'],
            'location' => $data['location'],
            'meeting_link' => $data['meeting_link'],
            'notes' => $data['notes'],
            'created_by' => Auth::id()
        ]);

        $this->interviewRepository->save(interview: $interview);
    }


}
