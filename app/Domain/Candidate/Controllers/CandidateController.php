<?php

namespace App\Domain\Candidate\Controllers;

use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use App\Domain\Candidate\Models\Candidate;
use App\Domain\Candidate\Requests\CreateCandidateRequest;
use App\Domain\Candidate\Requests\CreateMultipleCandidatesRequest;
use App\Utils\ApiResponderService;
use App\Utils\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CandidateController extends Controller
{
    public function __construct(
        protected CandidateServiceInterface $candidateService
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
