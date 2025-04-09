<?php

namespace App\Domain\CandidateStage\Exceptions;

use App\Shared\Exceptions\DomainException;
use Illuminate\Http\Response;

class CandidateStageCreationException extends DomainException
{
    public function __construct(
        protected ?string $message = null,
        protected ?array $errors = []
    ) {
        $message = $message ?: $this->defaultMessage();
        parent::__construct(
            resourceType: 'Candidate stage',
            customMessage: $message,
            httpCode: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function getErrors()
    {
        return $this->errors ?: [];
    }

    public function defaultMessage()
    {
        return "Failed to create candidate stage";
    }
}
