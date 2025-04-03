<?php

namespace App\Domain\Interviewer\Repositories;

use App\Domain\Interview\Models\Interviewer;
use App\Domain\Interviewer\Interfaces\InterviewerRepositoryInterface;

class EloquentInterviewerRepository implements InterviewerRepositoryInterface
{
    public function save(Interviewer $interviewer): void
    {
        $interviewer->saveOrFail();
    }
}
