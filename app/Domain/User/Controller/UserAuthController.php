<?php

namespace App\Domain\User\Controller;

use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Requests\UserLoginRequest;
use App\Domain\User\Requests\UserRegisterRequest;
use App\Shared\ApiResponderService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {}

    public function register(UserRegisterRequest $request, $departmentID)
    {
        $this->userService->register($request, $departmentID);
        return (new ApiResponderService)->successResponse('User created', Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request)
    {
        $token = $this->userService->login($request);
        return (new ApiResponderService)->successResponse('Login', Response::HTTP_OK, ['token' => $token]);
    }

    public function me()
    {
        return (new ApiResponderService)->successResponse('Me', Response::HTTP_OK, ['user' => $this->userService->getMe()]);

    }



}
