<?php

namespace App\Domain\Department\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class DepartmentNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        protected ?int $departmentId = null,
        protected ?string $customMessage = null

    )
    {
        parent::__construct(
            resourceType: 'Department',
            resourceId: $departmentId,
            customMessage: $customMessage ?: $this->createDefaultMessage()
        );
    }

    public function  createDefaultMessage()
    {
        $message = "Department";

        if($this->departmentId){
            $message .= " with ID: $this->departmentId";
        }

        return "$message not found";
    }
}
