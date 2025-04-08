<?php

namespace App\Domain\CandidateStage\Exceptions;

use DomainException;
use Illuminate\Http\Response;

class CandidateStageNotApprovedException extends DomainException
{
    public function __construct(
        protected ?int $candidateStageId = null,
        protected ?string $customMessage = null
    )
    {
        $message = $customMessage ?: $this->defaultMessage();
        parent::__construct(
            message: $message,
            code: Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    private function defaultMessage(): string
    {
        $message = "Candidate stage";

        if($this->candidateStageId){
            $message .= " with ID: $this->candidateStageId";
        }

        return $message . ' is not approved.';

    }
}
