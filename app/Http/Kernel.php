<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\EnsureRoleIsAuthenticated;


class Kernel extends HttpKernel
{
    protected $middleware = [
        // Middleware global
    ];

    protected $middlewareGroups = [
        'web' => [
            // Middleware untuk grup web
        ],

        'api' => [
            // Middleware untuk grup API
        ],
    ];

    protected $routeMiddleware = [
        // Middleware rute lainnya
        'auth.role' => \App\Http\Middleware\EnsureRoleIsAuthenticated::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}