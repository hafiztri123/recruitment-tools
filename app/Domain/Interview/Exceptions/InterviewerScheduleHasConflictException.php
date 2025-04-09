<?php

namespace App\Domain\Interview\Exceptions;

use App\Shared\Exceptions\DomainException;
use Illuminate\Http\Response;

class InterviewerScheduleHasConflictException extends DomainException
{
    public function __construct(
        protected ?string $message = null,
        protected ?array $errors = []
    )
    {
        $message = $message ?: $this->defaultMessage();
        parent::__construct(
            message: $message,
            code: Response::HTTP_UNPROCESSABLE_ENTITY
        );

    }

    public function getErrors()
    {
        return $this->errors ?: [];
    }

    public function defaultMessage()
    {
        return "User has a conflicting schedule.";

    }
}
