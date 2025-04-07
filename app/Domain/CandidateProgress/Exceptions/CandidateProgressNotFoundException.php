<?php

namespace App\Domain\CandidateProgress\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class CandidateProgressNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $candidateProgressId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Candidate progress',
            resourceId: $candidateProgressId,
            customMessage: $customMessage
        );
    }
}
