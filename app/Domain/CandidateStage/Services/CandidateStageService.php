<?php

namespace App\Domain\CandidateStage\Services;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Exceptions\CandidateProgressNotFoundException;
use App\Domain\CandidateProgress\Interfaces\CandidateProgressRepositoryInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\CandidateStage\Exceptions\CandidateApprovalCheckException;
use App\Domain\CandidateStage\Exceptions\CandidateBatchUpdateException;
use App\Domain\CandidateStage\Exceptions\CandidateRejectionException;
use App\Domain\CandidateStage\Exceptions\CandidateStageAlreadyCompletedException;
use App\Domain\CandidateStage\Exceptions\CandidateStageCreationException;
use App\Domain\CandidateStage\Exceptions\CandidateStageNotFoundException;
use App\Domain\CandidateStage\Exceptions\CandidateStageNotApprovedException;
use App\Domain\CandidateStage\Exceptions\CandidateStageUpdateException;
use App\Domain\CandidateStage\Exceptions\PreviousStageNotCompletedException;
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
use Illuminate\Support\Facades\Log;

class CandidateStageService implements CandidateStageServiceInterface
{
    public function __construct(
        protected CandidateStageRepositoryInterface $candidateStageRepository,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository,
        protected CandidateProgressRepositoryInterface $candidateProgressRepository,
        protected CandidateRepositoryInterface $candidateRepository,
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected ApprovalRepositoryInterface $approvalRepository
    ) {}

    public function createCandidateStage(int $order): int
    {
        try {
            $recruitmentStage = $this->recruitmentStageRepository->findByOrder(order: $order);

            $candidateStage = CandidateStage::make([
                'status' => 'pending',
                'scheduled_at' => now(),
            ]);

            $candidateStage->stage_id = $recruitmentStage->id;
            return $this->candidateStageRepository->create(candidateStage: $candidateStage);
        } catch (\Exception $e) {
            throw new CandidateStageCreationException(
                message: "Failed to create candidate stage with order {$order}: {$e->getMessage()}",
                errors: ['order' => $order]
            );
        }
    }

    public function moveCandidateToNextStage(
        int $candidateID,
        int $recruitmentBatchID,
    ): void {
        try {
            $candidateStage = $this->getCurrentCandidateStage($candidateID, $recruitmentBatchID);

            $this->validateStageStatus($candidateStage);
            $this->validatePreviousStages($candidateID, $recruitmentBatchID, $candidateStage);
            $this->validateRequiredApprovals($candidateID, $candidateStage);

            $this->completeCandidateStage($candidateStage, $recruitmentBatchID, $candidateID);
        } catch (
            CandidateNotFoundException | RecruitmentBatchNotFoundException |
            CandidateProgressNotFoundException | CandidateStageAlreadyCompletedException |
            PreviousStageNotCompletedException | CandidateStageNotApprovedException $e
        ) {
            // Re-throw domain exceptions as they are already properly typed
            throw $e;
        } catch (\Exception $e) {
            throw new CandidateStageUpdateException(
                candidateId: $candidateID,
                batchId: $recruitmentBatchID,
                customMessage: "Failed to move candidate to next stage: {$e->getMessage()}"
            );
        }
    }

    public function moveCandidatesToNextStage(CandidatesStageUpdateStatusRequest $request, int $batchID): void
    {
        try {
            $this->validateBatchExists($batchID);

            $candidatesID = $request->candidates;
            $this->dispatchCandidateUpdateJobs($candidatesID, $batchID);
            $this->handleRejectedCandidates($candidatesID, $batchID);
        } catch (CandidateNotFoundException | RecruitmentBatchNotFoundException $e) {
            // Re-throw domain exceptions
            throw $e;
        } catch (\Exception $e) {
            throw new CandidateBatchUpdateException(
                batchId: $batchID,
                customMessage: "Failed to move candidates to next stage: {$e->getMessage()}"
            );
        }
    }

    public function rejectCandidates(int $candidateID, int $recruitmentBatchID): void
    {
        try {
            $this->validateCandidateAndBatchExist($candidateID, $recruitmentBatchID);

            DB::transaction(function () use ($candidateID, $recruitmentBatchID) {
                $candidateStage = $this->getCurrentCandidateStage($candidateID, $recruitmentBatchID);

                $this->candidateStageRepository->updateCandidateStage(candidateStage: $candidateStage, data: [
                    'status' => 'failed',
                    'completed_at' => now(),
                    'passed' => false,
                ]);
            });
        } catch (CandidateNotFoundException | RecruitmentBatchNotFoundException | CandidateProgressNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CandidateRejectionException(
                candidateId: $candidateID,
                batchId: $recruitmentBatchID,
                customMessage: "Failed to reject candidate: {$e->getMessage()}"
            );
        }
    }

    public function getCurrentCandidateStage(
        int $candidateID,
        int $recruitmentBatchID
    ): CandidateStage {
        $this->validateCandidateAndBatchExist($candidateID, $recruitmentBatchID);

        $candidateProgresses = $this->candidateProgressRepository->findByCandidateIDAndRecruitmentBatchIDWithStages(
            candidateID: $candidateID,
            recruitmentBatchID: $recruitmentBatchID
        );

        if ($candidateProgresses->isEmpty()) {
            throw new CandidateProgressNotFoundException(customMessage: "Candidate progress with candidate ID: $candidateID and recruitment batch ID: $recruitmentBatchID not found");
        }

        $candidateStage = $candidateProgresses->last()->candidateStage;

        if (!$candidateStage) {
            throw new CandidateStageNotFoundException(customMessage: "Candidate stage not found for candidate ID: $candidateID and recruitment batch ID: $recruitmentBatchID");
        }

        return $candidateStage;
    }


    private function validateStageStatus(CandidateStage $candidateStage): void
    {
        if ($candidateStage->status === 'completed') {
            throw new CandidateStageAlreadyCompletedException(
                'This stage is already completed. Cannot update completed stages.'
            );
        }
    }

    private function validatePreviousStages(int $candidateID, int $recruitmentBatchID, CandidateStage $candidateStage): void
    {
        $currentStageOrder = $candidateStage->recruitmentStage->order;

        if ($currentStageOrder > 1) {
            $previousStageCompleted = $this->verifyPreviousStagesCompleted(
                candidateID: $candidateID,
                recruitmentBatchID: $recruitmentBatchID,
                currentStageOrder: $currentStageOrder
            );

            if (!$previousStageCompleted) {
                throw new PreviousStageNotCompletedException(
                    errors: ['current_stage_order' => $currentStageOrder]
                );
            }
        }
    }

    private function validateRequiredApprovals(int $candidateID, CandidateStage $candidateStage): void
    {
        $currentStageOrder = $candidateStage->recruitmentStage->order;
        $finalStageOrder = $this->recruitmentStageRepository->getFinalStageOrder();

        if ($currentStageOrder + 1 >= $finalStageOrder) {
            $hasRequiredApprovals = $this->candidateHasRequiredApprovals(candidateID: $candidateID);

            if (!$hasRequiredApprovals) {
                throw new CandidateStageNotApprovedException($candidateStage->id);
            }
        }
    }

    private function completeCandidateStage(CandidateStage $candidateStage, int $recruitmentBatchID, int $candidateID): void
    {
        DB::transaction(function () use ($candidateStage, $recruitmentBatchID, $candidateID) {
            $lockedCandidateStage = $this->candidateStageRepository->lockForUpdate(
                id: $candidateStage->id
            );

            $this->candidateStageRepository->updateCandidateStage(candidateStage: $lockedCandidateStage, data: [
                'status' => 'completed',
                'completed_at' => now(),
                'passed' => true,
            ]);

            DB::afterCommit(function() use ($candidateStage, $recruitmentBatchID, $candidateID){
                CandidateStageUpdated::dispatch($candidateStage, $recruitmentBatchID, $candidateID);
            });

        });
    }

    private function validateBatchExists(int $batchID): void
    {
        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $batchID)) {
            throw new RecruitmentBatchNotFoundException(recruitmentBatchId: $batchID);
        }
    }

    private function validateCandidateAndBatchExist(int $candidateID, int $recruitmentBatchID): void
    {
        if (!$this->candidateRepository->candidateExistsByID(id: $candidateID)) {
            throw new CandidateNotFoundException(candidateId: $candidateID);
        }

        if (!$this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $recruitmentBatchID)) {
            throw new RecruitmentBatchNotFoundException(recruitmentBatchId: $recruitmentBatchID);
        }
    }

    private function dispatchCandidateUpdateJobs(array $candidatesID, int $batchID): void
    {
        $chunkSize = 10;

        collect($candidatesID)->chunk(count($candidatesID),$chunkSize)->each(function ($candidateIdChunk) use ($batchID) {
            foreach ($candidateIdChunk as $candidateID) {
                if (!$this->candidateRepository->candidateExistsByID(id: $candidateID)) {
                    throw new CandidateNotFoundException(candidateId: $candidateID);
                }

                UpdateCandidateStageJob::dispatch($candidateID, $batchID);
            }
        });
    }

    private function handleRejectedCandidates(array $candidatesID, int $batchID): void
    {
        try {
            $rejectedCandidates = $this->candidateProgressRepository
                ->findByBatchIDAndExcludingByCandidateIds(batchID: $batchID, candidateIDs: $candidatesID);

            $this->dispatchRejectionJobs($rejectedCandidates, $batchID);
        } catch (CandidateProgressNotFoundException $e) {
        }
    }

    private function dispatchRejectionJobs($rejectedCandidates, int $batchID): void
    {
        $chunkSize = 10;

        collect($rejectedCandidates)->chunk($chunkSize)->each(function ($rejectedChunk) use ($batchID) {
            foreach ($rejectedChunk as $rejectedCandidate) {
                RejectCandidateJob::dispatch($rejectedCandidate->candidate_id, $batchID);
            }
        });
    }

    private function candidateHasRequiredApprovals(int $candidateID): bool
    {
        try {
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
        } catch (\Exception $e) {
            throw new CandidateApprovalCheckException(
                candidateId: $candidateID,
                customMessage: "Failed to check approvals: {$e->getMessage()}"
            );
        }
    }

    private function verifyPreviousStagesCompleted(int $candidateID, int $recruitmentBatchID, int $currentStageOrder): bool
    {
        try {
            $candidateProgresses = $this->candidateProgressRepository->findByCandidateIDAndRecruitmentBatchIDWithStages(
                candidateID: $candidateID,
                recruitmentBatchID: $recruitmentBatchID
            );

            // Debug output
            Log::info("Verifying previous stages for candidate: $candidateID, batch: $recruitmentBatchID, current stage: $currentStageOrder");
            foreach ($candidateProgresses as $progress) {
                $stage = $progress->candidateStage;
                $order = $stage->recruitmentStage->order;
                $status = $stage->status;
                $passed = $stage->passed ? 'true' : 'false';
                Log::info("Stage order: $order, status: $status, passed: $passed");
            }

            $completedStageOrders = $this->getCompletedStageOrders($candidateProgresses);
            Log::info("Completed stage orders: " . implode(', ', $completedStageOrders));

            $result = $this->areAllPreviousStagesCompleted($completedStageOrders, $currentStageOrder);
            Log::info("All previous stages completed: " . ($result ? 'true' : 'false'));

            return $result;
        } catch (CandidateProgressNotFoundException $e) {
            Log::error("CandidateProgressNotFoundException: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error("Exception in verifyPreviousStages: " . $e->getMessage());
            throw new CandidateStageUpdateException(
                candidateId: $candidateID,
                batchId: $recruitmentBatchID,
                customMessage: "Failed to verify previous stages: {$e->getMessage()}"
            );
        }
    }

    private function getCompletedStageOrders($candidateProgresses): array
    {
        $completed = [];

        foreach ($candidateProgresses as $progress) {
            $stage = $progress->candidateStage;
            $order = $stage->recruitmentStage->order;

            if ($stage->status === 'completed' && $stage->passed == true) {
                $completed[] = $order;
            }
        }

        Log::info("Debug completed stages: " . json_encode($completed));
        return $completed;
    }

    private function areAllPreviousStagesCompleted(array $completedStageOrders, int $currentStageOrder): bool
    {
        for ($order = 1; $order < $currentStageOrder; $order++) {
            if (!in_array($order, $completedStageOrders)) {
                return false;
            }
        }

        return true;
    }
}
