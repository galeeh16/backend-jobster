<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\AddContextMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(AddContextMiddleware::class);
        
        // $middleware->web(append: [
        //     HandleInertiaRequests::class,
        // ]);

        // $middleware->appendToGroup('jwt', [
        //     AuthMiddleware::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });

        $exceptions->render(function(ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });
    })->create();
