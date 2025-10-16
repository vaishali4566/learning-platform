<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthenticateUser;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'authenticate.user' => AuthenticateUser::class,
             'prevent.back.history' => \App\Http\Middleware\PreventBackHistory::class,
             'prevent.trainer.back' => \App\Http\Middleware\PreventTrainerBackHistory::class,
             'admin.only' => \App\Http\Middleware\AdminOnly::class,

        ]);
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
