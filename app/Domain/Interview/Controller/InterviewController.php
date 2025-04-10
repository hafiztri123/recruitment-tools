<?php

namespace App\Domain\Interview\Controller;

use App\Domain\Interview\Interfaces\InterviewServiceInterface;
use App\Domain\Interview\Models\Interview;
use App\Domain\Interview\Requests\CreateInterviewRequest;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ApiResponderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class InterviewController extends Controller
{
    public function __construct(private InterviewServiceInterface $interviewService)
    {

    }

    public function createInterview(CreateInterviewRequest $request)
    {
        Gate::authorize('create',Interview::class );
        $candidateStage = $request->route('candidate_stage_id');
        $interviewData = $request->validated();
        $interviewData['candidate_stage_id'] = $candidateStage;
        $this->interviewService->createInterview($interviewData);
        return (new ApiResponderService)->successResponse('Interview created', Response::HTTP_CREATED);
    }
}
