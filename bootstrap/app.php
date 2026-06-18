<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\ActiveCommunityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return; // Let Laravel handle JSON API errors natively
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return; // Let Laravel handle unauthenticated redirect to login
            }

            $statusCode = 500;
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
            }

            // If a custom view exists in our ErrorHandling folder, use it
            if (view()->exists("ErrorHandling.{$statusCode}")) {
                return response()->view("ErrorHandling.{$statusCode}", ['exception' => $e], $statusCode);
            }

            // Fallback for 500 if we are not in debug mode
            if ($statusCode === 500 && !config('app.debug') && view()->exists("ErrorHandling.500")) {
                return response()->view("ErrorHandling.500", ['exception' => $e], 500);
            }
            
            // Otherwise, let Laravel's default handler take over
        });
    })->create();
