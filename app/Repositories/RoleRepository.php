<?php

namespace App\Repositories;


interface RoleRepository
{
    public function findRoleBySlug(string $slug): int;
}
