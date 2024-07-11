<?php

namespace App\Http\Middleware;

use App\Jobs\LogRequest;
use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class RecordRequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $responseContent = json_decode($response->getContent(), true);
        $uuid = $responseContent['uuid'] ?? Uuid::uuid4()->toString();

        if (json_last_error() === JSON_ERROR_NONE) {
            $responseContent = $response->getContent();
        } else {
            $responseContent = json_encode([]);
        }

        $parameters = [
            'uuid' => $uuid,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'parameters' => json_encode($request->all()),
            'response' => $responseContent,
            'response_code' => $response->getStatusCode()
        ];

        LogRequest::dispatch($parameters);

        return $response;
    }
}
