<?php

namespace App\Domain\CandidateStage\Controllers;

use App\Domain\CandidateStage\Interfaces\CandidateStageServiceInterface;
use App\Domain\CandidateStage\Requests\CandidatesStageUpdateStatusRequest;
use App\Utils\ApiResponderService;
use App\Utils\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CandidateStageController extends Controller
{
    public function __construct(
        protected CandidateStageServiceInterface $candidateStageService
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
