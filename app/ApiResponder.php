<?php

namespace App;

trait ApiResponder
{
    function successResponseWithoutData(string $message, int $codeNumber = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message
        ], $codeNumber);
    }

    function successResponse(string $message, array $data, int $codeNumber = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], $codeNumber);
    }

    function errorResponse(string $message, string $code, int $codeNumber = 404, array $data = null)
    {
        return response()->json([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'details' => $data
        ], $codeNumber);
    }
}
