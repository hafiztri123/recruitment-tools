<?php

namespace App\Domain\Approval\Controller;

use App\Domain\Approval\Interfaces\ApprovalServiceInterface;
use App\Domain\Approval\Models\Approval;
use App\Domain\Approval\Requests\CreateApprovalRequest;
use App\Domain\Approval\Requests\UpdateApprovalRequest;
use App\Shared\ApiResponderService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApprovalController extends Controller
{
    public function __construct(
        private ApprovalServiceInterface $approvalService
    ) {}

    public function createApproval(CreateApprovalRequest $request)
    {
        Gate::authorize('create', Approval::class);

        $approvalData = $request->validated();
        $approvalData['candidate_id'] = $request->route('candidate_id');

        $this->approvalService->createApproval($approvalData);

        return (new ApiResponderService)->successResponse('Approval request created', Response::HTTP_CREATED);
    }

    public function updateApproval(UpdateApprovalRequest $request, $approvalId)
    {
        $approval = Approval::lockForUpdate()->findOrFail($approvalId);
        Gate::authorize('update', $approval);

        $approvalData = $request->validated();
        $approvalData['approver_id'] = Auth::id();

        $this->approvalService->updateApproval($approvalData);

        return (new ApiResponderService)->successResponse('Approval updated', Response::HTTP_OK);
    }

    public function getPendingApprovals()
    {
        $userId = Auth::id();
        $pendingApprovals = $this->approvalService->getPendingApprovalsByApproverId($userId);

        return (new ApiResponderService)->successResponse('Pending approvals', Response::HTTP_OK, ['approvals' => $pendingApprovals]);
    }

    public function getCandidateApprovals($candidateId)
    {
        Gate::authorize('viewAny', Approval::class);

        $approvals = $this->approvalService->getApprovalsByCandidateId($candidateId);

        return (new ApiResponderService)->successResponse('Candidate approvals', Response::HTTP_OK, ['approvals' => $approvals]);
    }
}
