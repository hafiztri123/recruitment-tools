<?php

namespace App\Domain\Interviewer\Policies;

use App\Domain\User\Models\User;
use App\Utils\PermissionService;

class InterviewerPolicy
{
    public function __construct(private PermissionService $permissionService)
    {}

    public function create(User $user): bool
    {
        return $this->permissionService->canManageRecruitment($user);
    }

    public function update(User $user): bool
    {
        return $this->permissionService->canFillInterviewerFeedback();
    }
}
