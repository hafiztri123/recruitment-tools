<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateStageController;
use App\Http\Controllers\HeadOfHRController;
use App\Http\Controllers\JobBatchController;
use App\Http\Controllers\RecruitmentBatchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('/v1')->group(function(){
    Route::post('/register/{department_id}', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->prefix('/v1')->group(function(){
    Route::post('/recruitments/create/{position_id}', [RecruitmentBatchController::class, 'createRecruitmentBatch']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/candidates/create/batch/{batch_id}', [CandidateController::class, 'createCandidates']);
    Route::post('/candidates/create/{batch_id}', [CandidateController::class, 'createCandidate']);
    Route::get('/candidates/jobs/{job_batch_id}', [JobBatchController::class, 'jobBatchStatus']);
    Route::patch('/candidates/next_stage/{recruitment_batch_id}', [CandidateStageController::class, 'moveCandidateStageToNextStep']);
});

