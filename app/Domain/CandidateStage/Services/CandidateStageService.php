<?php


namespace App\Domain\CandidateStage\Services;

use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Exceptions\CandidateProgressNotFoundException;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Jobs\RejectCandidateJob;
use App\Domain\CandidateStage\Jobs\UpdateCandidateStageJob;
use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\CandidateStage\Requests\CandidatesStageUpdateStatusRequest;
use App\Domain\RecruitmentBatch\Exceptions\RecruitmentBatchNotFoundException;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

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
            'scheduled_at' => now(),
        ]);

        $candidateStage->stage_id = $this->recruitmentStageRepository->findByOrder(order: $order)->id;
        return $this->candidateStageRepository->create(candidateStage: $candidateStage);
    }

    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ): void {

        DB::transaction(function () use ($candidateID, $recruitmentBatchID) {
            $candidateStage = $this->getCandidateStage($candidateID, $recruitmentBatchID);

            $lockedCandidateStage = $this->candidateStageRepository->lockForUpdate(
                id: $candidateStage->id
            );


            $this->candidateStageRepository->updateCandidateStage(candidateStage: $lockedCandidateStage, data: [
                'status' => 'completed',
                'completed_at' => now(),
                'passed' => true,
            ]);

            CandidateStageUpdated::dispatch($candidateStage, $recruitmentBatchID, $candidateID)->afterCommit();
        });


    }

    public function moveCandidatesToNextStage(CandidatesStageUpdateStatusRequest $request, int $batchID): void
    {
        $candidatesID = $request->candidates;

        foreach ($candidatesID as $candidateID) {
            UpdateCandidateStageJob::dispatch($candidateID, $batchID)
                ->onQueue('candidate-updates');
        }

        $rejectedCandidates = $this->candidateProgressRepository
            ->findByBatchIDAndExcludingByCandidateIds(batchID: $batchID, candidateIDs: $candidatesID);

        foreach ($rejectedCandidates as $rejectedCandidate) {
            RejectCandidateJob::dispatch($rejectedCandidate->id, $batchID)
                ->onQueue('candidate-rejections');
        }


    }

    public function rejectCandidates(
        int $candidateID,
        int $recruitmentBatchID
    ):void
    {
        $candidateStage = $this->getCandidateStage($candidateID, $recruitmentBatchID);

        $this->candidateStageRepository->updateCandidateStage(candidateStage: $candidateStage, data: [
            'status' => 'failed',
            'completed_at' => now(),
            'passed' => false,
        ]);
    }

    private function getCandidateStage(
        int $candidateID,
        int $recruitmentBatchID
    ): CandidateStage {
        if (!$this->candidateRepository->candidateExistsByID(id: $candidateID)) {
            throw new CandidateNotFoundException(candidateId: $candidateID);
        }

        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $recruitmentBatchID)) {
            throw new RecruitmentBatchNotFoundException(recruitmentBatchId: $recruitmentBatchID);
        }

        $candidateProgress = $this->candidateProgressRepository->findByCandidateIDAndRecruitmentBatchID(
                candidateID: $candidateID,
                recruitmentBatchID: $recruitmentBatchID
            );

        if ($candidateProgress->isEmpty()) {
            throw new CandidateProgressNotFoundException(customMessage: "Candidate progress with candidate ID: $candidateID and recruitment batch ID: $recruitmentBatchID not found");
        }

        $candidateStage = $this->candidateStageRepository->findById($candidateProgress->last()->candidate_stage_id);

        return $candidateStage;
    }
}
