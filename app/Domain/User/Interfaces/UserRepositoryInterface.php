<?php

namespace App\Domain\User\Interfaces;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(User $user): void;
    public function findByEmail(string $email): User;
    public function findMe(): User;
    public function existsById(int $id): bool;
    public function findUsersByRolesAndDepartment(
        array $requiredApproverRoles,
        int $departmentId
    ): Collection;
    public function getAllPotentialInterviewers(): Collection;
}
