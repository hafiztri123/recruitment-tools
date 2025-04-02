<?php


namespace App\Domain\User\Interfaces;

use App\Domain\User\Requests\UserLoginRequest;
use App\Domain\User\Requests\UserRegisterRequest;
use App\Domain\User\Resources\UserResource;

interface UserServiceInterface
{
    public function register(UserRegisterRequest $request, int $departmentID): void;
    public function login(UserLoginRequest $reqyest): string;
    public function getMe(): UserResource;
}
