<?php


namespace App\Repositories\Implementation;

use App\Models\CandidateProgress;
use App\Repositories\CandidateProgressRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidateProgressRepositoryImpl implements CandidateProgressRepository
{
    public function create(CandidateProgress $candidateProgress): void
    {
        $candidateProgress->saveOrFail();
    }

    public function findByCandidateIDAndRecruitmentBatchID(int $candidateID, int $recruitmentBatchID): Collection
    {
        $results = CandidateProgress::where('candidate_id', $candidateID)
            ->where('recruitment_batch_id', $recruitmentBatchID)
            ->orderBy('candidate_stage_id', 'asc')
            ->get();

        if($results->isEmpty()){
            throw new ModelNotFoundException('Candidate progress not found', 404);
        }

        return $results;
    }

}
