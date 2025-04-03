<?php

namespace App\Utils;

use App\Domain\User\Models\User;

class PermissionService
{
    public function canManageRecruitment(User $user): bool
    {
        return $user->hasAnyRole(slugs: ['hr', 'head-of-hr']);
    }
}
