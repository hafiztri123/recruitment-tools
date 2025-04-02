<?php

namespace App\Domain\Department\Repositories;

use App\Domain\Department\Interfaces\DepartmentRepositoryInterface;
use App\Domain\Department\Models\Department;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    public function departmentExists(int $id): bool
    {
        return Department::where('id', $id)->exists();
    }
}
