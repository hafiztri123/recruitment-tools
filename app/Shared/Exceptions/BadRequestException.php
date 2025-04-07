<?php

namespace App\Shared\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
    private $errors;

    public function __construct(string $message = 'Bad request', array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct(
            message: $message,
            code: Response::HTTP_BAD_REQUEST
        );
    }

    public function getErrors(): array
    {
        return $this->errors ?? [];
    }
}
