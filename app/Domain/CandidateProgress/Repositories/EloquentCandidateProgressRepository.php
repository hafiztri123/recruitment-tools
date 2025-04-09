<?php


namespace App\Domain\CandidateProgress\Repositories;

use App\Domain\CandidateProgress\Exceptions\CandidateProgressNotFoundException;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateProgress\Models\CandidateProgress;
use Illuminate\Database\Eloquent\Collection;

class EloquentCandidateProgressRepository implements CandidateProgressRepositoryInterface
{
    public function create(CandidateProgress $candidateProgress): void
    {
        $candidateProgress->saveOrFail();
    }

    public function findByCandidateIDAndRecruitmentBatchID(int $candidateID, int $recruitmentBatchID): Collection
    {
        $exists = CandidateProgress::where('candidate_id', $candidateID)
            ->where('recruitment_batch_id', $recruitmentBatchID)
            ->exists();

        if (!$exists) {
            throw new CandidateProgressNotFoundException();
        }

        return CandidateProgress::where('candidate_id', $candidateID)
            ->where('recruitment_batch_id', $recruitmentBatchID)
            ->orderBy('candidate_stage_id', 'asc')
            ->get();
    }

    public function findByBatchIDAndExcludingByCandidateIds(int $batchID, array $candidateIDs): Collection
    {
        $exists = CandidateProgress::where('recruitment_batch_id', $batchID)
            ->whereNotIn('candidate_id', $candidateIDs)
            ->exists();

        if (!$exists) {
            throw new CandidateProgressNotFoundException();
        }

        return CandidateProgress::where('recruitment_batch_id', $batchID)
            ->whereNotIn('candidate_id', $candidateIDs)
            ->get();
    }

    public function findByCandidateIDAndRecruitmentBatchIDWithStages(int $candidateID, int $recruitmentBatchID): Collection
    {
        $results = CandidateProgress::where('candidate_id', $candidateID)
            ->where('recruitment_batch_id', $recruitmentBatchID)
            ->with(['candidateStage.recruitmentStage'])
            ->orderBy('candidate_stage_id', 'asc')
            ->get();

        if($results->isEmpty()){
            throw new CandidateProgressNotFoundException();
        }

        return $results;
    }

}
