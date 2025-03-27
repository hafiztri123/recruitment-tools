<?php


namespace App\Repositories\Implementation;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RoleRepositoryImpl implements RoleRepository
{
    public function findRoleIDBySlug(string $slug): int
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        return $role->id;
    }
}
