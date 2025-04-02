<?php

namespace App\Domain\Role\Interfaces;

interface RoleRepositoryInterface
{
    public function findRoleIdBySlug(string $slug): int;
}
