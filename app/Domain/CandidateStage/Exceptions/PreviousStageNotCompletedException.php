<?php

namespace App\Domain\CandidateStage\Exceptions;

use App\Shared\Exceptions\DomainException;
use Illuminate\Http\Response;

class PreviousStageNotCompletedException extends DomainException
{
    public function __construct(
        protected ?string $customMessage = null,
        protected array $errors = []
    )
    {
        $message = $customMessage ?: $this->defaultMessage();
        parent::__construct(
            resourceType: 'Candidate stage',
            customMessage: $message,
            httpCode: Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    public function getErrors()
    {
        return $this->errors ?: [];
    }

    public function defaultMessage()
    {
        return "Previous recruitment stage was not completed";
    }
}
