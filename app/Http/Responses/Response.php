<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;

class Response
{
    /**
     * Return a generic success response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return self::formatResponse([
            'status' => 'success',
            'message' => __($message),
            'data' => $data
        ], $status);
    }

    private static function formatResponse(array $response, int $status): JsonResponse
    {
        if ($response['data'] === null) {
            unset($response['data']);
        }

        $response['uuid'] = Uuid::uuid4()->toString();

        return response()->json($response, $status);
    }

    /**
     * Return a generic error response
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $message, int $status = 400, mixed $data = null,): JsonResponse
    {
        return self::formatResponse([
            'status' => 'error',
            'message' => __($message),
            'data' => $data,
        ], $status);
    }
}
