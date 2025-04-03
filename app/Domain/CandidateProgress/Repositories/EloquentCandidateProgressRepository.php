<?php


namespace App\Domain\CandidateProgress\Repositories;

use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Models\CandidateProgress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentCandidateProgressRepository implements CandidateProgressRepositoryInterface
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

    public function findByBatchIDAndExcludingByCandidateIds(int $batchID, array $candidateIDs): Collection
    {
        $results =
            CandidateProgress::
            where('recruitment_batch_id', $batchID)->
            whereNotIn('candidate_id', $candidateIDs)->get();
        if ($results->isEmpty()){
            throw new ModelNotFoundException('Candidate progress not found', 404);
        }

        return $results;

    }

}
