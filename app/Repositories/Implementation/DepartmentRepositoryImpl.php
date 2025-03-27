<?php

namespace App\Repositories\Implementation;

use App\Models\Department;
use App\Repositories\DepartmentRepository;

class DepartmentRepositoryImpl implements DepartmentRepository
{
    public function departmentExists(int $id): bool
    {
        return Department::where('id', $id)->exists();
    }
}
