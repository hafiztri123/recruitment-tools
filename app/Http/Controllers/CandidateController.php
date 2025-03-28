<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCandidateRequest;
use App\Models\Candidate;
use App\Services\ApiResponderService;
use App\Services\CandidateService;
use Illuminate\Http\Request;
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
            $this->candidateService->createCandidate(request: $request, batchID: $batchID);
            return (new ApiResponderService)->successResponse('create candidate', Response::HTTP_CREATED);
    }


}
