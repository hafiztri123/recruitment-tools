<?php


namespace App\Domain\CandidateStage\Services;

use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Jobs\UpdateCandidateStageJob;
use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\CandidateStage\Requests\CandidatesStageUpdateStatusRequest;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;

class CandidateStageService implements CandidateStageServiceInterface
{


    public function __construct(
        protected CandidateStageRepositoryInterface $candidateStageRepository,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository,
        protected CandidateProgressRepositoryInterface $candidateProgressRepository,
        protected CandidateRepositoryInterface $candidateRepository,
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository
    ){}

    public function createCandidateStage(int $order): int
    {
        $candidateStage = CandidateStage::make([
            'status' => 'pending',
        ]);

        $candidateStage->stage_id = $this->recruitmentStageRepository->findByOrder(order: $order)->id;
        return $this->candidateStageRepository->create(candidateStage: $candidateStage);
    }

    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ): void {

        if(!$this->candidateRepository->candidateExistsByID(id: $candidateID)){
            throw new ModelNotFoundException('Candidate not found', 404);
        }

        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $recruitmentBatchID)){
            throw new ModelNotFoundException('Recruitment batch not found', 404);
        }

        //collection
        $candidateProgress = $this->candidateProgressRepository->findByCandidateIDAndRecruitmentBatchID(
            candidateID: $candidateID,
            recruitmentBatchID: $recruitmentBatchID
        );

        if($candidateProgress->isEmpty()){
            throw new ModelNotFoundException('Candidate progress not found', 404);
        }


        $candidateStage = $this->candidateStageRepository->findById($candidateProgress->last()->candidate_stage_id);

        $this->candidateStageRepository->updateCandidateStage(candidateStage: $candidateStage, data:[
            'status' => 'completed',
            'completed_at' => now(),
            'passed' => true,
        ]);

        CandidateStageUpdated::dispatch($candidateStage, $recruitmentBatchID, $candidateID);
    }

    public function moveCandidatesToNextStage(CandidatesStageUpdateStatusRequest $request, int $batchID): string
    {
        $jobs = collect($request->candidates)->map(function ($candidateID) use ($batchID){
            return new UpdateCandidateStageJob(
                candidateID: $candidateID,
                batchID: $batchID
            );
        })->toArray();

        $batch = Bus::batch($jobs)->dispatch();

        return $batch->id;


    }









}
