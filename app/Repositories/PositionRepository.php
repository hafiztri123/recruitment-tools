<?php


namespace App\Repositories;


interface PositionRepository
{
    public function positionExists(int $positionID): bool;
}
