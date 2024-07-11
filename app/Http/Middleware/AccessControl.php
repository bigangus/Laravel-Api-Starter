<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()->tokenCan($permission)) {
            return \App\Http\Responses\Response::error('Unauthorized', 403);
        }

        return $next($request);
    }
}
