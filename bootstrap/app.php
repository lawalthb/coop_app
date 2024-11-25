<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckAdminApproval;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    //   $middleware->alias('admin_sign', CheckAdminApproval::class);

    $middleware->alias([
        'admin_sign' => CheckAdminApproval::class,
        'is_admin' => AdminMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //testing ci cd
    })->create();
