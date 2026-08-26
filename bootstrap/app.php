<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\EditorOrAdmin;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TrackPageView;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'payment/midtrans/notification',
        ]);

        $middleware->redirectTo(
            guests: '/admin/login',
            users: '/admin'
        );

        $middleware->alias([
            'admin' => AdminOnly::class,
            'editor.or.admin' => EditorOrAdmin::class,
        ]);

        $middleware->web(append: [
            SetLocale::class,
            TrackPageView::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
