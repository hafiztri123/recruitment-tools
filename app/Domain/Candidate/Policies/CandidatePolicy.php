<?php

namespace App\Domain\Candidate\Policies;

use App\Domain\Candidate\Models\Candidate;
use App\Domain\User\Models\User;
use App\Utils\PermissionService;
use Illuminate\Auth\Access\Response;

class CandidatePolicy
{
    public function __construct(private PermissionService $permissionService)
    {}
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Candidate $candidate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->permissionService->canManageRecruitment($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Candidate $candidate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Candidate $candidate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Candidate $candidate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Candidate $candidate): bool
    {
        return false;
    }
}
