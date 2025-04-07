<?php

namespace App\Domain\Department\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class DepartmentNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $departmentId = null,
        ?string $customMessage = null

    )
    {
        parent::__construct(
            resourceType: 'Department',
            resourceId: $departmentId,
            customMessage: $customMessage
        );
    }
}
