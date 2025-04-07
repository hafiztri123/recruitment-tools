<?php

namespace App\Domain\RecruitmentBatch\Exceptions;

use App\Shared\Exceptions\ResourceNotFoundException;

class RecruitmentBatchNotFoundException extends ResourceNotFoundException
{
    public function __construct(
        ?int $recruitmentBatchId = null,
        ?int $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Recruitment batch',
            resourceId: $recruitmentBatchId,
            customMessage: $customMessage
        );
    }
}
