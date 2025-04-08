<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(User $user) :void
    {
            $user->saveOrFail();
    }

    public function findByEmail(string $email): User
    {
            return User::where('email', $email)->firstOrFail();
    }

    public function findMe(): User
    {
        return User::with('roles')->find(Auth::id());
    }

    public function existsById(int $id): bool
    {
        return User::where('id', $id)->exists();
    }

    public function findUsersByRolesAndDepartment(array $requiredApproverRoles, int $departmentId): Collection
    {
        return User::with('roles')
            ->where('department_id', $departmentId)
            ->whereHas('roles', function ($query) use ($requiredApproverRoles){
                $query->whereIn('slug', $requiredApproverRoles);
            })
            ->get();
    }
}
