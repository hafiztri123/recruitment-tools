<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function AssignRoles(User $user): Response
    {
        return $user->hasRole('head-of-hr')
        ? Response::allow()
        : Response::deny('ACTION_NOT_ALLOWED');
    }

    public function IsHeadOfHR(User $user): Response
    {
        return $user->hasRole('head-of-hr')
            ? Response::allow()
            : Response::deny('ACTION_NOT_ALLOWED');
    }

    public function CreateRecruitmentBatches(User $user): bool
    {
        return $user->hasRole(['hr', 'head-of-hr']);
    }
}
