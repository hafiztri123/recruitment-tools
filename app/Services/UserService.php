<?php


namespace App\Services;

use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;

interface UserService
{
    public function register(CreateUser $request, int $departmentID): void;
    public function login(LoginRequest $reqyest): string;
    public function getMe(): UserResource;
}
