<?php

namespace App\Shared\Exceptions;

use Exception;
use Illuminate\Http\Response;

class DomainException extends Exception
{

    public function __construct(
        protected string $resourceType,
        protected ?string $customMessage = null,
        protected ?int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        $this->resourceType = $resourceType;
        $message = $customMessage;

        parent::__construct(message: $message, code: $httpCode);
    }

    protected function getResourceType(): string
    {
        return $this->resourceType;
    }



}
