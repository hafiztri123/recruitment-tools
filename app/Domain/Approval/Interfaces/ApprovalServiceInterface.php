<?php

namespace App\Domain\Approval\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ApprovalServiceInterface
{
    public function createApproval(array $data): void;
    public function updateApproval(array $data): void;
    public function getPendingApprovalsByApproverId(int $approverId): Collection;
    public function getApprovalsByCandidateId(int $candidateId): Collection;
    public function checkIfCandidateHasAllApprovals(int $candidateId): bool;
    public function getRequiredApproversForCandidate(int $candidateID): array;
    public function requestRequiredApprovals(int $candidateID): void;
}
