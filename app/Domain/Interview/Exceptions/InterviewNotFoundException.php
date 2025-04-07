<?php

namespace App\Domain\Interview\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class InterviewNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $interviewId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Interview',
            resourceId: $interviewId,
            customMessage: $customMessage
        );
    }

}
