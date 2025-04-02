<?php

namespace App\Domain\User\Services;

use App\Domain\Department\Interfaces\DepartmentRepositoryInterface;
use App\Domain\Role\Interfaces\RoleRepositoryInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Models\User;
use App\Domain\User\Requests\UserLoginRequest;
use App\Domain\User\Requests\UserRegisterRequest;
use App\Domain\User\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{


    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected DepartmentRepositoryInterface $departmentRepository,
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function register(UserRegisterRequest $request, int $departmentID): void
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

        $this->assignRole(user: $user, slug: 'user');


    }

    public function login(UserLoginRequest $request): string
    {
        $user = $this->userRepository->findByEmail($request->email);
        $userHashedPassword = $user->password;

        if (!Hash::check($request->password, $userHashedPassword)){
            throw new AuthenticationException('Invalid credentials');
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    private function assignRole(User $user, string $slug): void
    {
        $roleID = $this->roleRepository->findRoleIDBySlug(slug: $slug);
        $user->roles()->attach(ids: $roleID);
    }

    public function getMe(): UserResource
    {
        $user = $this->userRepository->findMe();
        return new UserResource($user);
    }
}

