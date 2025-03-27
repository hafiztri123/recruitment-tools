<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponder;
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(CreateUser $request, $departmentID)
    {
        try{
            $this->userService->register($request, $departmentID);
            return $this->successResponse('User created', 201);
        } catch (\Exception $e) {
            return $this->failResponse($e->getMessage(), $e->getCode(), ['context' => $e]);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->userService->login($request);
            return $this->successResponse();
        } catch (\Exception $e){
            return $this->failResponse($e->getMessage(), 401, ['context' => $e]);
        }
    }



}
