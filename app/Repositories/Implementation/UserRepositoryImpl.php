<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRepositoryImpl implements UserRepository
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
