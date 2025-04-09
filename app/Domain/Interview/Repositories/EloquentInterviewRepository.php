<?php


namespace App\Domain\Interview\Repositories;

use App\Domain\Interview\Exceptions\InterviewNotFoundException;
use App\Domain\Interview\Interfaces\InterviewRepositoryInterface;
use App\Domain\Interview\Models\Interview;

class EloquentInterviewRepository implements InterviewRepositoryInterface
{
    public function save(Interview $interview): void
    {
        $interview->saveOrFail();
    }

    public function existsById(int $id): bool
    {
        return Interview::where('id', $id)->exists();
    }

    public function findById(int $id): Interview
    {
        return Interview::findOrFail($id);
    }

    public function findByIdWithDetails(int $id): Interview
    {
        $result =  Interview::with(['candidateStage'])
            ->where('id', $id)
            ->first();

        if(!$result){
            throw new InterviewNotFoundException($id);
        }

        return $result;
    }
}
