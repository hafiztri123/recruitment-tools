<?php


namespace App\Domain\Interview\Repositories;

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
}
