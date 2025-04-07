<?php

namespace App\Shared\Exceptions;

use Illuminate\Http\Response;

class ConflictException extends DomainException
{

    public function __construct(
        protected string $resourceType,
        protected ?int $resourceId = null,
        protected ?string $customMessage = null
    )
    {
        $message = $customMessage ?: $this->defaultMessage();

        parent::__construct(
            resourceType: $resourceType,
            customMessage: $customMessage,
            httpCode: Response::HTTP_CONFLICT
        );


    }


    protected function defaultMessage(): string
    {
        $message = "$this->resourceType";

        if ($this->resourceId) {
            $message .= " with identifier $this->resourceId already exists";
        }
        return $message . ".";
    }
}
