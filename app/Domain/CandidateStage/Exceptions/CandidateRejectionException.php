<?php

namespace App\Domain\CandidateStage\Exceptions;

use App\Shared\Exceptions\DomainException;
use Illuminate\Http\Response;

class CandidateRejectionException extends DomainException
{
    public function __construct(
        protected ?int $candidateId = null,
        protected ?int $batchId = null,
        protected ?string $customMessage = null
    ) {
        $message = $customMessage ?: $this->defaultMessage();
        parent::__construct(
            resourceType: 'Candidate stage',
            customMessage: $message,
            httpCode: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function defaultMessage()
    {
        $message = "Failed to reject candidate";

        if ($this->candidateId) {
            $message .= " ID: {$this->candidateId}";
        }

        if ($this->batchId) {
            $message .= " in batch ID: {$this->batchId}";
        }

        return $message;
    }
}
