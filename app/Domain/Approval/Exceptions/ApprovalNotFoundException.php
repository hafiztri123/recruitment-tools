<?php

namespace App\Domain\Approval\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class ApprovalNotFoundException extends ResourceNotFoundException
{
    public function __construct(?int $approvalId = null, ?string $customMessage = null)
    {
        parent::__construct(
            resourceType: 'Approval',
            resourceId: $approvalId,
            customMessage: $customMessage
        );

    }
}
