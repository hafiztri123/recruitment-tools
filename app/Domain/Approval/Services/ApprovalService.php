<?php

namespace App\Domain\Approval\Services;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\Approval\Models\Approval;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ApprovalService implements ApprovalServiceInterface
{
    public function __construct(
        private ApprovalRepositoryInterface $approvalRepository,
        private CandidateRepositoryInterface $candidateRepository,
        private UserRepositoryInterface $userRepository
    )
    {

    }

    public function createApproval(array $data): void
    {
        $this->approvalRequestValidation(data: $data);


        $approval = Approval::make([
            'candidate_id' => $data['candidate_id'],
            'approver_id' => $data['approver_id'],
            'status' => 'pending'
        ]);

        $this->approvalRepository->save($approval);
    }

    public function updateApproval(array $data): void
    {
        $this->approvalRequestValidation(data: $data);

        $approval = $this->approvalRepository->findByApproverIdAndCandidateIdAndStatusPending(
            approverId: $data['approver_id'],
            candidateId: $data['candidate_id']
        );

        $updatedApproval = $this->approvalUpdateRequestValidation(approval: $approval, data: $data);
        $this->approvalRepository->save(approval: $updatedApproval);
    }

    private function approvalRequestValidation(array $data){
        if (isset($data['candidate_id']) || isset($data['approver_id'])) {
            throw new BadRequestException('bad request', 400);
        }


        $candidateExists = $this->candidateRepository->candidateExistsByID(id: $data['candidate_id']);

        if (!$candidateExists) {
            throw new ModelNotFoundException('Candidate not found', 404);
        }

        $approverExists = $this->userRepository->existsById(id: $data['approver_id']);

        if (!$approverExists) {
            throw new ModelNotFoundException('Approver not found', 404);
        }
    }

    private function approvalUpdateRequestValidation(Approval $approval, array $data): Approval
    {
        if(isset($data['status']) && $data['status'] !== 'pending'){
            $approval->status = $data['status'];
            $approval->approved_at = now();
        }

        if(isset($data['comments'])){
            $approval->comments = $data['comments'];
        }

        return $approval;
    }


}
