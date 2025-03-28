<?php

namespace App\Services\Implementation;

use App\Events\CandidateCreated;
use App\Http\Requests\CreateCandidateRequest;
use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use App\Repositories\CandidateStageRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Services\CandidateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CandidateServiceImpl implements CandidateService
{
    public function __construct(
        protected RecruitmentBatchRepository $recruitmentBatchRepository,
        protected CandidateRepository $candidateRepository,
    ) {}


    public function createCandidate(CreateCandidateRequest $request, int $batchID): void
    {
       $recruitmentBatchExists = $this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $batchID);

       if(!$recruitmentBatchExists){
        throw new ModelNotFoundException('Recruitment batch not found', 404);
       }

       $candidate = $this->makeCandidate(request: $request);
       $this->candidateRepository->create(candidate: $candidate);

       //event listener
       CandidateCreated::dispatch($candidate, $batchID);

    }

    private function makeCandidate(CreateCandidateRequest $request): Candidate
    {
        $candidate = Candidate::make([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'resume_path' => $request->resume_path,
            'source' => $request->source,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);


        return $candidate;
    }

}
