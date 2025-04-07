<?php

namespace App\Shared\Exceptions;

use Illuminate\Http\Response;

class ResourceNotFoundException extends DomainException
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
            httpCode: Response::HTTP_NOT_FOUND
        );
    }


    protected function defaultMessage(): string
    {
        $message = "The requested $this->resourceType was not found";

        if ($this->resourceId) {
            $message .= " with identifier $this->resourceId";
        }
        return $message . ".";
    }
}
