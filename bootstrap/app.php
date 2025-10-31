<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $responseService = app(ResponseService::class);
            return match (true) {
                $e instanceof AuthenticationException =>
                $responseService->error(
                    Response::HTTP_UNAUTHORIZED,
                    'Unauthenticated. Please provide a valid token.'
                ),
                $e instanceof TokenExpiredException =>
                $responseService->error(
                    Response::HTTP_UNAUTHORIZED,
                    'Token has expired.'
                ),
                $e instanceof TokenInvalidException =>
                $responseService->error(
                    Response::HTTP_UNAUTHORIZED,
                    'Invalid token.'
                ),
                $e instanceof JWTException =>
                $responseService->error(
                    Response::HTTP_UNAUTHORIZED,
                    'Token not provided or malformed.'
                ),
                $e instanceof UnauthorizedHttpException =>
                $responseService->error(
                    Response::HTTP_UNAUTHORIZED,
                    'Unauthorized: Missing or invalid Bearer token.'
                ),
                default => null,
            };
        });
    })->create();
