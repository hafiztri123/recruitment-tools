<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Models\User;
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
}
