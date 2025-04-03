<?php

use App\Domain\Candidate\Controllers\CandidateController;
use App\Domain\CandidateStage\Controllers\CandidateStageController;
use App\Domain\Interview\Controller\InterviewController;
use App\Domain\Interviewer\Controller\InterviewerController;
use App\Domain\RecruitmentBatch\Controllers\RecruitmentBatchController;
use App\Domain\User\Controller\UserAuthController;
use App\Domain\User\Controller\UserController;
use App\Utils\Http\Controllers\JobBatchController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('/v1')->group(function(){
    Route::post('/register/{department_id}', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->prefix('/v1')->group(function(){
    Route::post('/recruitments/create/{position_id}', [RecruitmentBatchController::class, 'createRecruitmentBatch']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/candidates/create/batch/{batch_id}', [CandidateController::class, 'createCandidates']);
    Route::post('/candidates/create/{batch_id}', [CandidateController::class, 'createCandidate']);
    Route::get('/candidates/jobs/{job_batch_id}', [JobBatchController::class, 'jobBatchStatus']);
    Route::patch('/candidates/next_stage/{recruitment_batch_id}', [CandidateStageController::class, 'moveCandidateStageToNextStep']);
    Route::post('/interviews/interviewers/batch/{interview_id}', [InterviewerController::class, 'createInterviewers']);
    Route::post('/interviews/interviewers/{interview_id}/{user_id}', [InterviewerController::class, 'createInterviewer']);
    Route::post('/interviews/{candidate_stage_id}', [InterviewController::class, 'createInterview']);
});

