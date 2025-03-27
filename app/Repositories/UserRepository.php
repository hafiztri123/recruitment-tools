<?php

namespace App\Repositories;

use App\Http\Requests\CreateUser;
use App\Models\User;
use Exception;

interface UserRepository
{
    public function create(User $user): void;
    public function findByEmail(string $email): User;
}
