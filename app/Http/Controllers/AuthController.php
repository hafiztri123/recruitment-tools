<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\ApiResponderService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(CreateUser $request, $departmentID)
    {
        $this->userService->register($request, $departmentID);
        return (new ApiResponderService)->successResponse('User created', Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->userService->login($request);
        return (new ApiResponderService)->successResponse('Login', Response::HTTP_OK, ['token' => $token]);
    }

    public function me()
    {
        return (new ApiResponderService)->successResponse('Me', Response::HTTP_OK, ['user' => $this->userService->getMe()]);

    }



}
