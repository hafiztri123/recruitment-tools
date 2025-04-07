<?php

namespace App\Domain\Approval\Policies;

use App\Domain\Approval\Models\Approval;
use App\Domain\User\Models\User;
use App\Shared\PermissionService;

class ApprovalPolicy
{
    public function __construct(private PermissionService $permissionService) {}

    public function create(User $user): bool
    {
        return $this->permissionService->canManageRecruitment($user);
    }

    public function update(User $user, Approval $approval): bool
    {
        // Only the assigned approver can update the approval status
        return $approval->approver_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $this->permissionService->canManageRecruitment($user);
    }

    public function view(User $user, Approval $approval): bool
    {
        // HR can view all approvals, approvers can view their own
        return $this->permissionService->canManageRecruitment($user) ||
            $approval->approver_id === $user->id;
    }
}
