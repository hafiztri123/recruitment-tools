<?php

namespace App\Repositories;


interface RoleRepository
{
    public function findRoleIDBySlug(string $slug): int;
}
