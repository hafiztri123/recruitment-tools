<?php


namespace App\Domain\Role\Repositories;

use App\Domain\Role\Interfaces\RoleRepositoryInterface;
use App\Domain\Role\Models\Role;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function findRoleIdBySlug(string $slug): int
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        return $role->id;
    }
}
