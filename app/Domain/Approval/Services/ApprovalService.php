<?php

namespace App\Domain\Approval\Services;

use App\Domain\Approval\Interfaces\ApprovalRepositoryInterface;
use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\Approval\Models\Approval;
use App\Domain\Candidate\Exceptions\CandidateNotFoundException;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\CandidateProgress\Exceptions\CandidateProgressNotFoundException;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Shared\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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

        DB::transaction(function () use ($data) {
            $approval = $this->createApprovalModel($data);
            $this->approvalRepository->save($approval);
        });
    }

    public function updateApproval(array $data): void
    {
        $this->validateApprovalRequestData(data: $data);

        DB::transaction(function () use ($data) {
            $approval = $this->getApprovalForUpdate($data);

            if (!isset($data['candidate_id'])) {
                $data['candidate_id'] = $approval->candidate_id;
            }

            $this->updateApprovalModel($approval, $data);
            $this->approvalRepository->save(approval: $approval);
        });
    }

    public function getPendingApprovalsByApproverId(int $approverId): Collection
    {
        return $this->approvalRepository->findByApproverIdAndStatusPending(approverId: $approverId);
    }

    public function getApprovalsByCandidateId(int $candidateId): Collection
    {
        $this->validateCandidateExists($candidateId);
        return $this->approvalRepository->findByCandidateIdAndStatusPending(candidateId: $candidateId);
    }

    public function checkIfCandidateHasAllApprovals(int $candidateId): bool
    {
        $pendingApprovals = $this->approvalRepository->findByCandidateIdAndStatusPending(candidateId: $candidateId);
        return $pendingApprovals->isEmpty();
    }

    public function getRequiredApproversForCandidate(int $candidateID): array
    {
        $requiredApproverRoles = $this->getRequiredApproverRoles();
        Log::info("Required approver roles: " . implode(", ", $requiredApproverRoles));

        $candidate = $this->candidateRepository->findCandidateWithProgressAndBatch($candidateID);
        $departmentId = $this->getDepartmentIdFromCandidate($candidate);
        Log::info("Department ID for candidate {$candidateID}: {$departmentId}");

        $approvers = $this->userRepository->findUsersByRolesAndDepartment(
            $requiredApproverRoles,
            $departmentId
        );

        if ($approvers->isEmpty()) {
            Log::warning("No approvers found with roles [" . implode(", ", $requiredApproverRoles) . "] in department {$departmentId}");
        } else {
            foreach ($approvers as $approver) {
                Log::info("Found approver: ID {$approver->id}, Name {$approver->name}, Department {$approver->department_id}");
            }
        }

        return $approvers->toArray();
    }
    public function requestRequiredApprovals(int $candidateID): void
    {
        Log::info("Starting approval request process for candidate ID: {$candidateID}");

        $requiredApprovers = $this->getRequiredApproversForCandidate($candidateID);
        Log::info("Found " . count($requiredApprovers) . " required approvers");

        if (empty($requiredApprovers)) {
            Log::warning("No approvers found for candidate ID: {$candidateID}. Check if department heads and HR heads exist in the system.");
        }

        $existingApproverIds = $this->approvalRepository->findApproverIdsByCandidateId($candidateID);
        Log::info("Found " . count($existingApproverIds) . " existing approver IDs");

        $approversNeedingApproval = $this->filterApproversNeeded($requiredApprovers, $existingApproverIds);
        Log::info("After filtering, " . $approversNeedingApproval->count() . " approvers need to create approval for");

        $this->createApprovalRequestsForApprovers($approversNeedingApproval, $candidateID);
    }


    private function createApprovalModel(array $data): Approval
    {
        return Approval::make([
            'candidate_id' => $data['candidate_id'],
            'approver_id' => $data['approver_id'],
            'status' => 'pending',
            'comments' => $data['comments'] ?? null
        ]);
    }

    private function getApprovalForUpdate(array $data): Approval
    {
        return $this->approvalRepository->findByApproverIdAndCandidateIdAndStatusPending(
            approverId: $data['approver_id'],
            candidateId: $data['candidate_id']
        );
    }

    private function updateApprovalModel(Approval $approval, array $data): void
    {
        $approval->status = $data['status'];
        $approval->comments = $data['comments'] ?? $approval->comments;
        $approval->approved_at = now();
    }

    private function validateCandidateExists(int $candidateId): void
    {
        if (!$this->candidateRepository->candidateExistsByID(id: $candidateId)) {
            throw new CandidateNotFoundException(candidateId: $candidateId);
        }
    }

    private function validateApprovalRequestData(array $data): void
    {
        $this->validateRequiredFields($data);
        $this->validateEntityExistence($data);
    }

    private function validateRequiredFields(array $data): void
    {
        if (!isset($data['candidate_id']) || !isset($data['approver_id'])) {
            $errors = [];

            if (!isset($data['candidate_id'])) {
                $errors['candidate_id'] = 'Candidate ID is missing';
            }

            if (!isset($data['approver_id'])) {
                $errors['approver_id'] = 'Approver ID is missing';
            }

            if (!empty($errors)) {
                throw new BadRequestException('Missing required data', $errors);
            }
        }
    }

    private function validateEntityExistence(array $data): void
    {
        $candidateExists = $this->candidateRepository->candidateExistsByID(id: $data['candidate_id']);
        if (!$candidateExists) {
            throw new CandidateNotFoundException(candidateId: $data['candidate_id']);
        }

        $approverExists = $this->userRepository->existsById(id: $data['approver_id']);
        if (!$approverExists) {
            throw new UserNotFoundException(userId: $data['approver_id'], customMessage: 'Approver not found');
        }
    }

    private function getRequiredApproverRoles(): array
    {
        $roles = Config::get('recruitment.approvers.roles', ['head-of-hr', 'department-head']);
        Log::info("Required approver roles from config: " . implode(', ', $roles));
        return $roles;
    }

    private function getDepartmentIdFromCandidate($candidate): int
    {
        $latestProgress = $candidate->candidateProgresses->sortByDesc('created_at')->first();
        if (!$latestProgress) {
            throw new CandidateProgressNotFoundException();
        }

        $recruitmentBatch = $latestProgress->recruitmentBatch;
        $position = $recruitmentBatch->position;
        return $position->department_id;
    }

    private function filterApproversNeeded(array $requiredApprovers, array $existingApproverIds): \Illuminate\Support\Collection
    {
        return collect($requiredApprovers)->filter(function ($approver) use ($existingApproverIds) {
            return !in_array($approver['id'], $existingApproverIds);
        });
    }

    private function createApprovalRequestsForApprovers(\Illuminate\Support\Collection $approversNeedingApproval, int $candidateID): void
    {
        $approversNeedingApproval->chunk(50)->each(function ($approverChunk) use ($candidateID) {
            DB::transaction(function () use ($approverChunk, $candidateID) {
                $approvalsToCreate = [];
                foreach ($approverChunk as $approver) {
                    // Access id as array key instead of object property
                    $approvalsToCreate[] = [
                        'candidate_id' => $candidateID,
                        'approver_id' => $approver['id'],
                        'status' => 'pending',
                        'comments' => 'Automatically generated approval request',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($approvalsToCreate)) {
                    Approval::insert($approvalsToCreate);
                }
            });
        });
    }
}
