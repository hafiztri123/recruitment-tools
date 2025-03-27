<?php


namespace App\Services;

use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use Exception;

interface UserServiceInterface
{
    public function register(CreateUser $request, int $departmentID): void;
    public function login(LoginRequest $reqyest): string;
}
