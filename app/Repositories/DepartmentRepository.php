<?php

namespace App\Repositories;

interface DepartmentRepository
{
    public function departmentExists(int $id): bool;
}
