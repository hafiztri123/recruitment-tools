<?php

namespace App\Domain\Approval\Interfaces;

use App\Domain\Approval\Models\Approval;
use Illuminate\Database\Eloquent\Collection;

interface ApprovalRepositoryInterface
{
    public function save(Approval $approval): void;
    public function findByApproverIdAndStatusPending(int $approverId): Collection;
    public function findByCandidateIdAndStatusPending(int $candidateId): Collection;
    public function findByCandidateId(int $candidateId):Collection;
    public function findByApproverIdAndCandidateIdAndStatusPending(int $approverId, int $candidateId): Approval;
    public function approvalExists(int $candidateID, int $approverID): bool;
}

