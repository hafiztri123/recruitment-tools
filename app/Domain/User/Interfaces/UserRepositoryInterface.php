<?php

namespace App\Domain\User\Interfaces;

use App\Domain\User\Models\User;

interface UserRepositoryInterface
{
    public function create(User $user): void;
    public function findByEmail(string $email): User;
    public function findMe(): User;
}
