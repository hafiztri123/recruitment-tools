<?php

namespace App\Domain\Approval\Services;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\Approval\Models\Approval;
use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Shared\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Collection;

class ApprovalService implements ApprovalServiceInterface
{
    public function __construct(
        private ApprovalRepositoryInterface $approvalRepository,
        private CandidateRepositoryInterface $candidateRepository,
        private UserRepositoryInterface $userRepository,
        private RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        private PositionRepositoryInterface $positionRepository
    ) {}

    public function createApproval(array $data): void
    {
        $this->validateApprovalRequestData($data);

        $approval = Approval::make([
            'candidate_id' => $data['candidate_id'],
            'approver_id' => $data['approver_id'],
            'status' => 'pending',
            'comments' => $data['comments'] ?? null
        ]);

        $this->approvalRepository->save($approval);
    }

    public function updateApproval(array $data): void
    {
        $this->validateApprovalRequestData(data: $data);

        $approval = $this->approvalRepository->findByApproverIdAndCandidateIdAndStatusPending(
            approverId: $data['approver_id'],
            candidateId: $data['candidate_id']
        );

        $approval->status = $data['status'];
        $approval->comments = $data['comments'] ?? $approval->comments;
        $approval->approved_at = now();

        $this->approvalRepository->save(approval: $approval);
    }

    public function getPendingApprovalsByApproverId(int $approverId): Collection
    {
        return $this->approvalRepository->findByApproverIdAndStatusPending(approverId: $approverId);
    }

    public function getApprovalsByCandidateId(int $candidateId): Collection
    {
        if (!$this->candidateRepository->candidateExistsByID(id: $candidateId)) {
            throw new CandidateNotFoundException(candidateId: $candidateId);
        }

        return $this->approvalRepository->findByCandidateIdAndStatusPending(candidateId: $candidateId);
    }

    public function checkIfCandidateHasAllApprovals(int $candidateId): bool
    {
        $pendingApprovals = $this->approvalRepository->findByCandidateIdAndStatusPending(candidateId: $candidateId);

        return $pendingApprovals->isEmpty();
    }

    private function validateApprovalRequestData(array $data): void
    {
        if (!isset($data['candidate_id']) || !isset($data['approver_id'])) {
            $errors = [];

            if(!isset($data['candidate_id'])){
                $errors['candidate_id'] = 'Candidate ID is missing';
            }

            if (!isset($data['approver_id'])) {
                $errors['approver_id'] = 'Approver ID is missing';
            }

            if(!empty($errors)){
                throw new BadRequestException('Missing required data', $errors);
            }

        }

        $candidateExists = $this->candidateRepository->candidateExistsByID(id: $data['candidate_id']);
        if (!$candidateExists) {
            throw new CandidateNotFoundException(candidateId: $data['candidate_id']);
        }

        $approverExists = $this->userRepository->existsById(id: $data['approver_id']);
        if (!$approverExists) {
            throw new UserNotFoundException(userId: $data['approver_id'], customMessage: 'Approver not found');
        }
    }

    public function getRequiredApproversForCandidate(int $candidateID): array
    {
        $requiredApproverRoles = ['head-of-hr', 'department-head'];

        $candidate = $this->candidateRepository->findCandidateById($candidateID);
        $recruitmentBatch = $this->recruitmentBatchRepository->findRecruitmentBatchByID($candidate->recruitmentBatch->id);
        $position = $this->positionRepository->findById($recruitmentBatch->position_id);
        $departmentId = $position->department_id;

        $approvers = $this->userRepository->findUsersByRolesAndDepartment(
            $requiredApproverRoles,
            $departmentId
        )->toArray();

        return $approvers;
    }

    public function requestRequiredApprovals(int $candidateID): void
    {
        $requiredApprovers = $this->getRequiredApproversForCandidate($candidateID);

        foreach ($requiredApprovers as $approver) {
            if (!$this->approvalRepository->approvalExists($candidateID, $approver->id)) {
                $this->createApproval([
                    'candidate_id' => $candidateID,
                    'approver_id' => $approver->id,
                    'comments' => 'Automatically generated approval request'
                ]);
            }
        }
    }
}
