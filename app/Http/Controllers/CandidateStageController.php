<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidatesStageUpdateStatusRequest;
use App\Services\ApiResponderService;
use App\Services\CandidateStageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidateStageController extends Controller
{
    public function __construct(
        protected CandidateStageService $candidateStageService
    ){}

    public function moveCandidateStageToNextStep(CandidatesStageUpdateStatusRequest $request)
    {
        $batchID = $request->route('recruitment_batch_id');
        $jobBatchID = $this->candidateStageService->moveCandidatesToNextStage(request: $request, batchID: $batchID);
        return (new ApiResponderService)->successResponse('Move candidate stage accepted', Response::HTTP_ACCEPTED, [
            'job_batch_id' => $jobBatchID
        ]);
    }
}
