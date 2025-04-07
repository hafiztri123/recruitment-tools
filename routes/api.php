<?php

use App\Domain\Approval\Controller\ApprovalController;
use App\Domain\Candidate\Controllers\CandidateController;
use App\Domain\CandidateStage\Controllers\CandidateStageController;
use App\Domain\Interview\Controller\InterviewController;
use App\Domain\Interviewer\Controller\InterviewerController;
use App\Domain\RecruitmentBatch\Controllers\RecruitmentBatchController;
use App\Domain\User\Controller\UserAuthController;
use App\Domain\User\Controller\UserController;
use App\Utils\Http\Controllers\JobBatchController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware('guest')->prefix('/v1')->group(function () {
    Route::post('/departments/{department_id}/users', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login'])->name('login');
});

// Authenticated routes
Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {
    // User routes
    Route::get('/me', [UserController::class, 'me']);

    // Job batch status
    Route::get('/job-batches/{job_batch_id}', [JobBatchController::class, 'jobBatchStatus']);

    // Recruitment batch routes
    Route::post('/positions/{position_id}/recruitment-batches', [RecruitmentBatchController::class, 'createRecruitmentBatch']);

    // Candidate routes
    Route::post('/recruitment-batches/{batch_id}/candidates', [CandidateController::class, 'createCandidate']);
    Route::post('/recruitment-batches/{batch_id}/candidates/batch', [CandidateController::class, 'createCandidates']);
    Route::patch('/recruitment-batches/{recruitment_batch_id}/candidates/stage', [CandidateStageController::class, 'moveCandidateStageToNextStep']);

    // Interview routes
    Route::post('/candidate-stages/{candidate_stage_id}/interviews', [InterviewController::class, 'createInterview']);

    // Interviewer routes
    Route::post('/interviews/{interview_id}/interviewers', [InterviewerController::class, 'createInterviewers']);
    Route::post('/interviews/{interview_id}/interviewers/{user_id}', [InterviewerController::class, 'createInterviewer']);
    Route::post('/interviews/{interview_id}/feedback', [InterviewerController::class, 'fillInterviewerFeedbackForm']);

    // Approval routes
    Route::post('/candidates/{candidate_id}/approvals', [ApprovalController::class, 'createApproval']);
    Route::put('/approvals/{approval_id}', [ApprovalController::class, 'updateApproval']);
    Route::get('/approvals/pending', [ApprovalController::class, 'getPendingApprovals']);
    Route::get('/candidates/{candidate_id}/approvals', [ApprovalController::class, 'getCandidateApprovals']);
});
