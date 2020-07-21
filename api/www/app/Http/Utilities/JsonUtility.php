<?php

namespace App\Http\Utilities;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonUtility
{
    public static function respond(bool $success, string $message, int $code = Response::HTTP_OK, array $data = []): JsonResponse
    {
        return response()->json(array_merge(
            [
                'success' => $success,
                'message' => $message
            ],
            $data
        ), $code);
    }
}
