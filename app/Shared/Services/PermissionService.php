<?php

namespace App\Shared;

use App\Domain\User\Models\User;

class PermissionService
{
    public function canManageRecruitment(User $user): bool
    {
        return $user->hasAnyRole(slugs: ['hr', 'head-of-hr']);
    }

    public function canFillInterviewerFeedback(): bool
    {
        return true;
    }
}
