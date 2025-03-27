<?php

namespace App\Repositories\Implementation;

use App\Models\Position;
use App\Repositories\PositionRepository;

class PositionRepositoryImpl implements PositionRepository
{
    public function positionExists(int $positionID): bool
    {
        return Position::where('id', $positionID)->exists();
    }
}
