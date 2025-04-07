<?php

namespace App\Domain\Position\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class PositionNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $positionId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Position',
            resourceId: $positionId,
            customMessage: $customMessage
        );
    }
}
