<?php

namespace App\Domain\User\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class UserNotFoundException extends ResourceNotFoundException
{
    public function __construct(?int $userId = null, ?string $customMessage = null)
    {
        parent::__construct(resourceType: 'User', resourceId: $userId, customMessage: $customMessage);
    }
}
