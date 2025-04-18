<?php

namespace App\Domain\Approval\Repositories;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Approval\Models\Approval;
use Illuminate\Database\Eloquent\Collection;

class EloquentApprovalRepository implements ApprovalRepositoryInterface
{
    public function save(Approval $approval): void
    {
        $approval->saveOrFail();
    }

    public function findByApproverIdAndStatusPending(int $approverId): Collection
    {
        $exists = Approval::where('approver_id', $approverId)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            return new Collection();
        }

        return Approval::where('approver_id', $approverId)
            ->where('status', 'pending')
            ->get();
    }

    public function findByCandidateIdAndStatusPending(int $candidateId): Collection
    {
        $exists = Approval::where('candidate_id', $candidateId)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            return new Collection();
        }

        return Approval::where('candidate_id', $candidateId)
            ->where('status', 'pending')
            ->get();
    }

    public function findByApproverIdAndCandidateIdAndStatusPending(int $approverId, int $candidateId): Approval
    {
        return Approval::where('approver_id', $approverId)
            ->where('candidate_id', $candidateId)
            ->firstOrFail();
    }

    public function findByCandidateId(int $candidateId): Collection
    {
        $exists = Approval::where('candidate_id', $candidateId)->exists();

        if (!$exists) {
            return new Collection();
        }

        return Approval::where('candidate_id', $candidateId)->get();
    }

    public function approvalExists(int $candidateID, int $approverID): bool
    {
        return Approval::where('candidate_id', $candidateID)
            ->where('approver_id', $approverID)
            ->exists();
    }

    public function findApproverIdsByCandidateId(int $candidateId): array
    {
        return Approval::where('candidate_id', $candidateId)
            ->pluck('approver_id')
            ->toArray();
    }


}
