<?php

namespace App\Http\Controllers;

use App\ApiResponder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use ApiResponder;

    public function Register(Request $request)
    {
        $request->validate([
            'name' => ['string', 'required'],
            'email' => ['string', 'email', 'unique:users,email', 'required'],
            'password' => [
                'confirmed',
                'string',
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->symbols()
                    ->numbers()
            ],
            'department_name' => ['string', 'required']
        ]);

        $departmentID = DB::table('departments')
            ->whereRaw('LOWER(name) = ?', [strtolower($request->department_name)])
            ->value('id');

        if (!$departmentID) {
            return $this->errorResponse(
                'Department Not Found',
                'NOT_FOUND',
                404,
                [
                    'field' => 'department_name',
                    'error' => 'Must be a valid department'
                ]
            );
        }

        $userRoleID = DB::table('roles')->where('slug', 'user')->value('id');

        try {
            DB::transaction(function () use ($request, $departmentID, $userRoleID) {
                $userID = DB::table('users')->insertGetId([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'department_id' => $departmentID,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('role_user')->insert([
                    'user_id' => $userID,
                    'role_id' => $userRoleID,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            });

            return $this->successResponseWithoutData('User created', 201);
        } catch (\Exception $e) {
            return $this->errorResponse(
                'User creation failed',
                'SERVER_ERROR',
                500,
                [
                    'error' => $e->getMessage()
                ]
            );
        }
    }


    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(
                'Invalid Credentials',
                'UNAUTHORIZED',
                401
            );
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return $this->successResponse('Login successful', ['token' => $token], 200);
    }

    public function Logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return $this->successResponseWithoutData('Successfully logged out', 200);
    }
}
