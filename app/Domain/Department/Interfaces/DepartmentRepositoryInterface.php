<?php

namespace App\Domain\Department\Interfaces;

interface DepartmentRepositoryInterface
{
    public function departmentExists(int $id): bool;
}
