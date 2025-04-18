<?php

namespace App\Domain\Interviewer\Controller;

use App\Domain\Interviewer\Interfaces\InterviewerServiceInterface;
use App\Domain\Interviewer\Models\Interviewer;
use App\Domain\Interviewer\Requests\CreateInterviewerRequest;
use App\Domain\Interviewer\Requests\CreateMultipleInterviewerRequest;
use App\Domain\Interviewer\Requests\InterviewerFillFeedbackRequest;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ApiResponderService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class InterviewerController extends Controller
{
    public function __construct(
        private InterviewerServiceInterface $interviewerService
    ) { }

    public function createInterviewer(CreateInterviewerRequest $request)
    {
        Gate::authorize('create', Interviewer::class);

        $interviewerData = $request->validated();
        $interviewerData['interview_id'] = $request->route('interview_id');
        $interviewerData['user_id'] = $request->route('user_id');

        $this->interviewerService->assignInterviewer($interviewerData);

        return (new ApiResponderService)->successResponse('Interviewer created', Response::HTTP_CREATED);
    }

    public function createInterviewers(CreateMultipleInterviewerRequest $request)
    {
        Gate::authorize('create', Interviewer::class);

        $interviewersData = $request->validated();
        $interviewersData['interview_id'] = $request->route('interview_id');

        $jobID = $this->interviewerService->assignInterviewers(multipleData: $interviewersData);

        return (new ApiResponderService)->successResponse('Batch create interviewer jobs accepted', Response::HTTP_ACCEPTED, [
            'job_id' => $jobID
        ]);
    }

    public function fillInterviewerFeedbackForm(InterviewerFillFeedbackRequest $request)
    {
        Gate::authorize('update', Interviewer::class);
        $interviewerData = $request->validated();
        $interviewerData['interview_id'] = $request->route('interview_id');

        $this->interviewerService->interviewerFillFeedback(data: $interviewerData);

        return (new ApiResponderService)->successResponse('Interviewer feedback filled', Response::HTTP_OK);
    }


}
