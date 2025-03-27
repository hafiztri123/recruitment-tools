<?php

namespace App\Services\Implementation;

use App\Http\Requests\CreateUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;


class UserServiceImpl implements UserService
{


    public function __construct(
        protected UserRepository $userRepository,
        protected DepartmentRepository $departmentRepository,
        protected RoleRepository $roleRepository
    ) {}

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

        $this->assignRole(user: $user, slug: 'user');


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

    private function assignRole(User $user, string $slug): void
    {
        $roleID = $this->roleRepository->findRoleIDBySlug(slug: $slug);
        $user->roles()->attach(ids: $roleID);
    }
}
    