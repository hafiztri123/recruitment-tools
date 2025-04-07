<?php

namespace App\Shared\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AuthenticationException extends Exception
{
    public function __construct(
        string $message = 'Authentication failed',
        protected array $details = []
    )
    {
        parent::__construct($message, Response::HTTP_UNAUTHORIZED);
    }

    public function getDetails()
    {
        return $this->details ?? [];
    }
}
