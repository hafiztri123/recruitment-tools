<?php

namespace App\Services\Implementation;

use App\Events\CandidateCreated;
use App\Http\Requests\CreateCandidateRequest;
use App\Http\Requests\CreateMultipleCandidatesRequest;
use App\Http\Requests\CreateMultipleCandidatesRequests;
use App\Jobs\CreateCandidateJob;
use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use App\Repositories\CandidateStageRepository;
use App\Repositories\PositionRepository;
use App\Repositories\RecruitmentBatchRepository;
use App\Services\CandidateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CandidateServiceImpl implements CandidateService
{
    public function __construct(
        protected RecruitmentBatchRepository $recruitmentBatchRepository,
        protected CandidateRepository $candidateRepository,
        protected PositionRepository $positionRepository
    ) {}


    public function createCandidate(array $candidateData, int $batchID): void
    {
       $recruitmentBatchExists = $this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $batchID);

       if(!$recruitmentBatchExists){
        throw new ModelNotFoundException('Recruitment batch not found', 404);
       }

       $candidate = $this->makeCandidate(candidateData: $candidateData);
       $this->candidateRepository->create(candidate: $candidate);

       //event listener
       CandidateCreated::dispatch($candidate, $batchID);

    }

    public function createCandidates(CreateMultipleCandidatesRequest $request, int $batchID): string
    {
        $candidates = $request['candidates'];

        $jobs = collect($candidates)->map(function ($candidate) use ($batchID){
            return new CreateCandidateJob($candidate, $batchID);
        })->toArray();

        $batch = Bus::batch($jobs)
            ->then(function ($batch) use ($batchID){
                $recruitmentBatch = $this->recruitmentBatchRepository->findRecruitmentBatchByID($batchID);
                $position = $this->positionRepository->findByID($recruitmentBatch->position_id);
                Log::info("Recruitment batch: {$recruitmentBatch->name} Position: {$position->name} created successfully");
        })
        ->dispatch();

        return $batch->id;
    }

    private function makeCandidate(array $candidateData): Candidate
    {
        $candidate = Candidate::make([
            'first_name' => $candidateData['first_name'],
            'last_name' => $candidateData['last_name'],
            'email' => $candidateData['email'],
            'phone' => $candidateData['phone'] ?? null,
            'whatsapp' => $candidateData['whatsapp'] ?? null,
            'resume_path' => $candidateData['resume_path'] ?? null,
            'source' => $candidateData['source'] ?? null,
            'status' => $candidateData['status'] ?? 'new',
            'notes' => $candidateData['notes'] ?? null,
        ]);

        return $candidate;
    }

}
