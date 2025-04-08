<?php


namespace App\Domain\CandidateStage\Services;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Exceptions\CandidateProgressNotFoundException;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Exceptions\CandidateStageAlreadyCompletedException;
use App\Domain\CandidateStage\Exceptions\CandidateStageNotApprovedException;
use App\Domain\CandidateStage\Interfaces\CandidateStageRepositoryInterface;
use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Jobs\RejectCandidateJob;
use App\Domain\CandidateStage\Jobs\UpdateCandidateStageJob;
use App\Domain\CandidateStage\Models\CandidateStage;
use App\Domain\CandidateStage\Requests\CandidatesStageUpdateStatusRequest;
use App\Domain\RecruitmentBatch\Exceptions\RecruitmentBatchNotFoundException;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CandidateStageService implements CandidateStageServiceInterface
{


    public function __construct(
        protected CandidateStageRepositoryInterface $candidateStageRepository,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository,
        protected CandidateProgressRepositoryInterface $candidateProgressRepository,
        protected CandidateRepositoryInterface $candidateRepository,
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected ApprovalRepositoryInterface $approvalRepository
    ){}

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    public function createCandidateStage(int $order): int
    {
        $candidateStage = CandidateStage::make([
            'status' => 'pending',
            'scheduled_at' => now(),
        ]);

        $candidateStage->stage_id = $this->recruitmentStageRepository->findByOrder(order: $order)->id;
        return $this->candidateStageRepository->create(candidateStage: $candidateStage);
    }

    /***************************************************
     *
     *
     *
     * * Purpose: Passing candidate to the next stage
     *
     *
     *
     *
     **************************************************/

    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ): void {

        $candidateStage = $this->getCurrentCandidateStage($candidateID, $recruitmentBatchID);

        if($candidateStage->status === 'completed'){
            throw new CandidateStageAlreadyCompletedException(
                'This stage is already completed. Cannot update completed stages.'
            );
        }

        $currentStageOrder = $candidateStage->recruitmentStage->order;



        $finalStageOrder = $this->recruitmentStageRepository->getFinalStageOrder();

        if ($currentStageOrder + 1 >= $finalStageOrder) {
            $hasRequiredApprovals = $this->candidateHasRequiredApprovals(candidateID: $candidateID);

            if (!$hasRequiredApprovals) {
                throw new CandidateStageNotApprovedException($candidateStage->id);
            }
        }

        if($currentStageOrder === $finalStageOrder - 1){

        }

        DB::transaction(function () use ($candidateStage, $recruitmentBatchID, $candidateID) {

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

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     *
     **************************************************/


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

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    public function rejectCandidates(
        int $candidateID,
        int $recruitmentBatchID
    ):void
    {
        $candidateStage = $this->getCurrentCandidateStage($candidateID, $recruitmentBatchID);

        $this->candidateStageRepository->updateCandidateStage(candidateStage: $candidateStage, data: [
            'status' => 'failed',
            'completed_at' => now(),
            'passed' => false,
        ]);
    }

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    public function getCurrentCandidateStage(
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

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

    private function candidateHasRequiredApprovals(int $candidateID): bool
    {
        $approvals = $this->approvalRepository->findByCandidateId($candidateID);

        if ($approvals->isEmpty()) {
            return true;
        }

        $pendingApprovals = $approvals->where('status', 'pending');
        if ($pendingApprovals->isNotEmpty()) {
            return false;
        }

        $rejectedApprovals = $approvals->where('status', 'rejected');
        if ($rejectedApprovals->isNotEmpty()) {
            return false;
        }

        return true;
    }

    /***************************************************
     *
     *
     *
     * * Purpose:
     *
     *
     *
     **************************************************/

     private function verifyPreviousStagesCompleted(
        int $candidateID,
        int $recruitmentBatchID,
        int $currentStageOrder
     ): bool {

        $candidateProgresses = $this->candidateProgressRepository->findByCandidateIDAndRecruitmentBatchID(
            candidateID: $candidateID,
            recruitmentBatchID: $recruitmentBatchID
        );

        $completedStageOrders = [];
        foreach($candidateProgresses as $candidateProgress) {
            $candidateStageID = $candidateProgress->candidate_stage_id;
            $candidateStage = $this->candidateStageRepository->findById(id: $candidateStageID);
            if ($candidateStage->status === 'completed' && $candidateStage->passed = true) {
                $completedStageOrders[] = $candidateStage->recruitmentStage->order;
            }
        }

        for ($order = 1; $order < $currentStageOrder; $order++){
            if (!in_array($order, $completedStageOrders)){
                return false;
            }
        }

        return true;
     }
}
