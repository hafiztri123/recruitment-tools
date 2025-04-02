<?php

namespace App\Domain\RecruitmentBatch\Policies;

use App\Domain\RecruitmentBatch\Models\RecruitmentBatch;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Log;

class RecruitmentBatchPolicy
{
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
    public function view(User $user, RecruitmentBatch $recruitmentBatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        Log::info($user->id);
        return $user->hasAnyRole(slugs: ['hr', 'head-of-hr']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecruitmentBatch $recruitmentBatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecruitmentBatch $recruitmentBatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RecruitmentBatch $recruitmentBatch): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RecruitmentBatch $recruitmentBatch): bool
    {
        return false;
    }
}
