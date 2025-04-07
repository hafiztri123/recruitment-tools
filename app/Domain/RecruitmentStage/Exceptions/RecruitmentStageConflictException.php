<?php

namespace App\Domain\RecruitmentStage\Exceptions;

use App\Shared\Exceptions\ConflictException;

class RecruitmentStageConflictException extends ConflictException
{
    public function __construct(
        ?int $recruitmentStageId = null,
        ?string $customMessage = null
    )
    {
        parent::__construct(
            resourceType: 'Recruitment stage',
            resoourceId: $recruitmentStageId,
            customMessage: $customMessage
        );
    }
}
