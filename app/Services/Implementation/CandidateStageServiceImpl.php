<?php


namespace App\Services\Implementation;

use App\Events\CandidateStageUpdated;
use App\Http\Requests\CandidatesStageUpdateStatusRequest;
use App\Jobs\UpdateCandidateStageJob;
use App\Models\CandidateStage;
use App\Repositories\CandidateProgressRepository;
use App\Repositories\CandidateRepository;
use App\Repositories\CandidateStageRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Repositories\RecruitmentStageRepository;
use App\Services\CandidateStageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;

class CandidateStageServiceImpl implements CandidateStageService
{


    public function __construct(
        protected CandidateStageRepository $candidateStageRepository,
        protected RecruitmentStageRepository $recruitmentStageRepository,
        protected CandidateProgressRepository $candidateProgressRepository,
        protected CandidateRepository $candidateRepository,
        protected RecruitmentBatchRepository $recruitmentBatchRepository
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
