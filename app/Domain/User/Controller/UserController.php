<?php

namespace App\Domain\User\Controller;

use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Requests\UserLoginRequest;
use App\Domain\User\Requests\UserRegisterRequest;
use App\Utils\ApiResponderService;
use App\Utils\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {}


    public function me()
    {
        return (new ApiResponderService)->successResponse('Me', Response::HTTP_OK, ['user' => $this->userService->getMe()]);
    }

}
