<?php

namespace App\Domain\CandidateStage\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class CandidateStageNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $candidateStageId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Candidate stage',
            resourceId: $candidateStageId,
            customMessage: $customMessage
        );
    }
}
