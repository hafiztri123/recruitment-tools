<?php

namespace App\Shared\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

trait ApiResponder
{

    function successResponse(
        string $message = 'success',
        int $statusCode = Response::HTTP_OK,
        AnonymousResourceCollection|Model|Paginator|Collection|Authenticatable|int|array $data = []
        ): Response {
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        if($data !== [] && $data !== null) {
            $response['data'] = $data;
        }

        return response($response, $statusCode);
    }

    function failResponse(
        string $message = 'fail',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        AnonymousResourceCollection|Model|Paginator|Collection|int|array|string|null $errors = null,
    ): Response {
        $response = [
            'status' => 'fail',
            'message' => $message
        ];

        if($errors !== [] && $errors !== null){
            $response['errors'] = $errors;
        }

        return response($response, $statusCode);

    }
}
