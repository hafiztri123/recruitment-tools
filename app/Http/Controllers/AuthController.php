<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponder;
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function register(CreateUser $request, $departmentID)
    {
        try{
            $this->userService->register($request, $departmentID);
            return $this->successResponseWithoutData('User created', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode(), ['context' => $e]);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->userService->login($request);
            return $this->successResponse('Login success',['token' => $token],  200);
        } catch (\Exception $e){
            return $this->errorResponse($e->getMessage(), 401, ['context' => $e]);
        }
    }



}
