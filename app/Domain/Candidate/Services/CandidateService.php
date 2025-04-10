<?php

namespace App\Domain\Candidate\Services;

use App\Domain\Candidate\Events\CandidateCreated;
use App\Domain\Candidate\Interfaces\CandidateRepositoryInterface;
use App\Domain\Candidate\Interfaces\CandidateServiceInterface;
use App\Domain\Candidate\Jobs\CreateCandidateJob;
use App\Domain\Candidate\Models\Candidate;
use App\Domain\Candidate\Requests\CreateMultipleCandidatesRequest;
use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\RecruitmentBatch\Exceptions\RecruitmentBatchNotFoundException;
use App\Domain\RecruitmentBatch\Interfaces\RecruitmentBatchRepositoryInterface;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function () use ($candidateData, $batchID) {
            $this->validateBatchExists($batchID);

            $candidate = $this->makeCandidate(candidateData: $candidateData);
            $this->candidateRepository->create(candidate: $candidate);

            DB::afterCommit(function () use ($candidate, $batchID) {
                CandidateCreated::dispatch($candidate, $batchID);
            });
        });
    }

    public function createCandidates(CreateMultipleCandidatesRequest $request, int $batchID): string
    {
        $this->validateBatchExistsOutsideTransaction($batchID);

        $candidates = $request['candidates'];
        $chunkSize = 10;

        $jobBatch = $this->createJobBatch($batchID);
        $this->addCandidateJobsToJobBatch($candidates, $jobBatch, $batchID, $chunkSize);

        return $jobBatch->id;
    }

    private function makeCandidate(array $candidateData): Candidate
    {
        return Candidate::make([
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
    }

    private function validateBatchExists(int $batchID): void
    {
        $recruitmentBatchExists = $this->recruitmentBatchRepository->recruitmentBatchExistsByID(id: $batchID);

        if (!$recruitmentBatchExists) {
            throw new RecruitmentBatchNotFoundException(recruitmentBatchId: $batchID);
        }
    }

    private function validateBatchExistsOutsideTransaction(int $batchID): void
    {
        DB::transaction(function () use ($batchID) {
            $this->validateBatchExists($batchID);
        });
    }

    private function createJobBatch(int $batchID): object
    {
        return Bus::batch([])
            ->then(function ($batch) use ($batchID) {
                $this->logBatchCompletion($batchID);
            })
            ->name('Process candidate imports - Batch ' . $batchID)
            ->dispatch();
    }

    private function logBatchCompletion(int $batchID): void
    {
        $recruitmentBatch = $this->recruitmentBatchRepository->findRecruitmentBatchWithPosition($batchID);
        $position = $recruitmentBatch->position;
        Log::info("Recruitment batch: {$recruitmentBatch->name} Position: {$position->name} created successfully");
    }

    private function addCandidateJobsToJobBatch(array $candidates, object $jobBatch, int $batchID, int $chunkSize): void
    {
        collect($candidates)->chunk($chunkSize)->each(function ($candidateChunk) use ($jobBatch, $batchID) {
            $jobs = $candidateChunk->map(function ($candidate) use ($batchID) {
                return new CreateCandidateJob($candidate, $batchID);
            })->toArray();

            $jobBatch->add($jobs);
        });
    }
}
