<?php

namespace App\Domain\Candidate\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class CandidateNotFoundException extends ResourceNotFoundException
{
    public function __construct(?int $candidateId = null, ?string $customMessage = null)
    {
        parent::__construct(
            resourceType: 'Candidate',
            resourceId: $candidateId,
            customMessage: $customMessage
        );
    }
}
