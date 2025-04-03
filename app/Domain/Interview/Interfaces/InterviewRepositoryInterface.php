<?php


namespace App\Domain\Interview\Interfaces;

use App\Domain\Interview\Models\Interview;

interface InterviewRepositoryInterface
{
    public function save(Interview $interview): void;
    public function existsById(int $id): bool;
}
