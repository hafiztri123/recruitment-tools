<?php

namespace App\Services\Implementation;

use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class UserServiceImpl implements UserService
{
    //TODO: BAD PRACTICE, FIX LATER
    private $HR = 1;
    private $USER = 2;
    private $HEAD_OF_HR = 3;

    protected UserRepository $userRepository;
    protected DepartmentRepository $departmentRepository;

    public function __construct(UserRepository $userRepository, DepartmentRepository $departmentRepository)
    {
        $this->userRepository = $userRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function register(CreateUser $request, int $departmentID): void
    {
        if (!$this->departmentRepository->departmentExists($departmentID)) {
            throw new ModelNotFoundException('Resource not found', 404);
        }

        $user = User::make([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $departmentID
        ]);

        $this->userRepository->create($user);

        $user->roles()->attach(2);

    }

    public function login(LoginRequest $request): string
    {
        $user = $this->userRepository->findByEmail($request->email);
        $userHashedPassword = $user->password;

        if (!Hash::check($request->password, $userHashedPassword)){
            throw new AuthenticationException('Invalid credentials');
        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}
