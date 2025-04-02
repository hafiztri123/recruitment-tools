<?php

namespace App\Domain\Position\Repositories;

use App\Domain\Position\Interfaces\PositionRepositoryInterface;
use App\Domain\Position\Models\Position;

class EloquentPositionRepository implements PositionRepositoryInterface
{
    public function positionExists(int $positionID): bool
    {
        return Position::where('id', $positionID)->exists();
    }

    public function findByID(int $positionID): Position
    {
        return Position::where('id', $positionID)->firstOrFail();
    }
}
