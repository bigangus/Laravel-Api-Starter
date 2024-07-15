<?php

use App\Http\Middleware\AccessControl;
use App\Http\Middleware\RecordRequestLog;
use App\Http\Responses\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        health: '/',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->redirectGuestsTo(function () {
            throw new AuthenticationException('Unauthenticated.');
        });

        $middleware->alias([
            'acl' => AccessControl::class,
        ]);

        $middleware->append(RecordRequestLog::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return Response::error($e->getMessage(), 422, $e->validator->errors());
        });

        $exceptions->render(function (RouteNotFoundException|NotFoundHttpException $e) {
            return Response::error($e->getMessage(), 404);
        });

        $exceptions->render(function (AuthenticationException $e) {
            return Response::error($e->getMessage(), 401);
        });

        $exceptions->render(function (HttpException $e) {
            return Response::error($e->getMessage(), $e->getStatusCode());
        });

        $exceptions->render(function (Throwable $e) {
            return Response::error($e->getMessage(), 500);
        });
    })
    ->create();
