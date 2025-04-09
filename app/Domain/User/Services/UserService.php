<?php

namespace App\Domain\User\Services;

use App\Domain\Department\Exceptions\DepartmentNotFoundException;
use App\Domain\Department\Interfaces\DepartmentRepositoryInterface;
use App\Domain\Role\Interfaces\RoleRepositoryInterface;
use App\Domain\User\Interfaces\UserRepositoryInterface;
use App\Domain\User\Interfaces\UserServiceInterface;
use App\Domain\User\Models\User;
use App\Domain\User\Requests\UserLoginRequest;
use App\Domain\User\Requests\UserRegisterRequest;
use App\Domain\User\Resources\UserResource;
use App\Shared\Exceptions\AuthenticationException as ExceptionsAuthenticationException;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function () use ($request, $departmentID) {
            $this->validateDepartmentExists($departmentID);

            $user = $this->createUserModel($request, $departmentID);
            $this->userRepository->create($user);

            $this->assignRole(user: $user, slug: 'user');
        });
    }

    public function login(UserLoginRequest $request): string
    {
        $user = $this->userRepository->findByEmail($request->email);
        $this->validatePassword($request->password, $user->password);

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function getMe(): UserResource
    {
        $user = $this->userRepository->findMe();
        return new UserResource($user);
    }


    private function validateDepartmentExists(int $departmentID): void
    {
        if (!$this->departmentRepository->departmentExists($departmentID)) {
            throw new DepartmentNotFoundException(departmentId: $departmentID);
        }
    }

    private function createUserModel(UserRegisterRequest $request, int $departmentID): User
    {
        return User::make([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $departmentID
        ]);
    }

    private function assignRole(User $user, string $slug): void
    {
        $roleID = $this->roleRepository->findRoleIDBySlug(slug: $slug);
        $user->roles()->attach(ids: $roleID);
    }

    private function validatePassword(string $providedPassword, string $userHashedPassword): void
    {
        if (!Hash::check($providedPassword, $userHashedPassword)) {
            throw new ExceptionsAuthenticationException(
                details: [
                    'credential' => 'Invalid credentials'
                ]
            );
        }
    }
}
