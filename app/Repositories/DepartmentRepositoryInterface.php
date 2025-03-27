<?php

namespace App\Repositories;

interface DepartmentRepositoryInterface{
    public function departmentExists(int $id): bool;
}
