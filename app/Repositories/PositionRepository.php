<?php


namespace App\Repositories;

use App\Models\Position;

interface PositionRepository
{
    public function positionExists(int $positionID): bool;
    public function findByID(int $positionID): Position;
}
