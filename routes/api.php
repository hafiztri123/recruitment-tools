<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HeadOfHRController;
use App\Http\Controllers\RecruitmentBatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user();

    // Get the user's roles
    $roles = DB::table('role_user')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('user_id', $user->id)
        ->pluck('roles.slug')
        ->toArray();

    // Return both user and roles
    return [
        'user' => $user,
        'roles' => $roles
    ];
})->middleware('auth:sanctum');
Route::middleware('guest')->prefix('/v1')->group(function(){
    Route::post('/login', [AuthController::class, 'Login']);
    Route::post('/register', [AuthController::class, 'Register']);
});

Route::prefix('/v1')->middleware('auth:sanctum')->group(function(){
    Route::put('/assign/hr/{user_id}', [HeadOfHRController::class, 'AssignHR']);
    Route::post('/create/recruitment', [RecruitmentBatchController::class, 'CreateRecruitmentBatches']);
    Route::post('/logout', [AuthController::class, 'Logout']);
});
