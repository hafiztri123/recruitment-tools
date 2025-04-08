<?php

namespace App\Domain\CandidateStage\Listeners;

use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\CandidateStage\Events\CandidateStageUpdated;
use App\Domain\RecruitmentStage\Interfaces\RecruitmentStageRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestApprovalForNextStage implements ShouldQueue
{
    public function __construct(
        protected ApprovalServiceInterface $approvalService,
        protected RecruitmentStageRepositoryInterface $recruitmentStageRepository
    )    { }

    public function handle(CandidateStageUpdated $event): void
    {
        $stage = $event->candidateStage;
        $stageOrder = $stage->order;

        $candidateID = $event->candidateID;
        $finalStageOrder = $this->recruitmentStageRepository->getFinalStageOrder();

        if ($stageOrder === $finalStageOrder - 1){
            $this->approvalService->requestRequiredApprovals($candidateID);
        }
    }
}
