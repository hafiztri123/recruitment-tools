<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCandidateRequest;
use App\Http\Requests\CreateMultipleCandidatesRequest;
use App\Models\Candidate;
use App\Services\ApiResponderService;
use App\Services\CandidateService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CandidateController extends Controller
{
    public function __construct(
        protected CandidateService $candidateService
    ){}

    public function createCandidate(CreateCandidateRequest $request)
    {
            Gate::authorize('create', Candidate::class);
            $batchID = $request->route('batch_id');
            $candidateData = $request->validated();
            $this->candidateService->createCandidate(candidateData: $candidateData, batchID: $batchID);
            return (new ApiResponderService)->successResponse('create candidate', Response::HTTP_CREATED);
    }

    public function createCandidates(CreateMultipleCandidatesRequest $request)
    {
        Gate::authorize('create', Candidate::class);
        $batchID = $request->route('batch_id');
        $jobsBatchID = $this->candidateService->createCandidates(request: $request, batchID: $batchID);
        return (new ApiResponderService)->successResponse('candidates creation jobs accepted', Response::HTTP_ACCEPTED, ['jobs_batch_id' => $jobsBatchID]);
    }


}
