<?php

namespace App\Repositories\Implementation;

use App\Models\Department;
use App\Repositories\DepartmentRepositoryInterface;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    public function departmentExists(int $id): bool
    {
        return Department::where('id', $id)->exists();
    }
}
