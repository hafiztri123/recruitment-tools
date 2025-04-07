<?php

namespace App\Shared\JobBatches\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class JobBatchNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $jobBatchId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Job batch',
            resourceId: $jobBatchId,
            customMessage: $customMessage
        );

    }
}
