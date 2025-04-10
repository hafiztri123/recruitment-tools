<?php

namespace App\Domain\CandidateStage\Listeners;

use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class RequestApprovalForNextStage implements ShouldQueue
{
    public function __construct(
        protected ApprovalServiceInterface $approvalService,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository
    )    { }

    public function handle(CandidateStageUpdated $event): void
    {
        $stage = $event->candidateStage;
        $stageOrder = $stage->recruitmentStage->order;

        $candidateID = $event->candidateID;
        $finalStageOrder = $this->recruitmentStageRepository->getFinalStageOrder();

        Log::info("RequestApprovalForNextStage: Stage order: {$stageOrder}, Final stage order: {$finalStageOrder}");

        if ($stageOrder === $finalStageOrder - 1) {
            Log::info("Requesting approvals for candidate: {$candidateID}");
            $this->approvalService->requestRequiredApprovals($candidateID);
        } else {
            Log::info("Not requesting approvals stage {$stageOrder} is not penultimate stage ($finalStageOrder - 1)");
        }
    }
}
