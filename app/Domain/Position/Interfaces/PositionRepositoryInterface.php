<?php


namespace App\Domain\Position\Interfaces;

use App\Domain\Position\Models\Position;

interface PositionRepositoryInterface
{
    public function positionExists(int $positionID): bool;
    public function findByID(int $positionID): Position;
}
