<?php


namespace App\Domain\Interview\Interfaces;

use App\Domain\Interview\Models\Interview;

interface InterviewRepositoryInterface
{
    public function save(Interview $interview): void;
    public function existsById(int $id): bool;
    public function findById(int $id): Interview;
    public function findByIdWithDetails(int $id): Interview;
}
