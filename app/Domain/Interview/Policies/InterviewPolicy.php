<?php

namespace App\Domain\Interview\Policies;

use App\Domain\Candidate\Policies\CandidatePolicy;
use App\Domain\User\Models\User;
use App\Shared\Services\PermissionService;

class InterviewPolicy
{
    public function __construct(private PermissionService $permissionService)
    { }


    public function create(User $user): bool
    {
        return $this->permissionService->canManageRecruitment($user);
    }
}

