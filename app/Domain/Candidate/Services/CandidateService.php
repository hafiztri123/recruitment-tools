<?php

namespace App\Domain\Candidate\Services;

use App\Domain\Candidate\Events\CandidateCreated;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use App\Domain\Candidate\Jobs\CreateCandidateJob;
use App\Domain\Candidate\Models\Candidate;
use App\Domain\Candidate\Requests\CreateMultipleCandidatesRequest;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CandidateService implements CandidateServiceInterface
{
    public function __construct(
        protected RecruitmentBatchRepositoryInterface $recruitmentBatchRepository,
        protected CandidateRepositoryInterface $candidateRepository,
        protected PositionRepositoryInterface $positionRepository
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
