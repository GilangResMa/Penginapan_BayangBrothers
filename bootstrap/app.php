<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    // Security middleware applied globally
    $middleware->append(\App\Http\Middleware\IPBlockingMiddleware::class);
    $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
    $middleware->append(\App\Http\Middleware\XSSProtectionMiddleware::class);
    $middleware->append(\App\Http\Middleware\InputValidationMiddleware::class);

    // Rate limiting middleware
    $middleware->throttleApi('60,1'); // Built-in API rate limiting

    // Custom middleware aliases
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
        'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
        'xss.protection' => \App\Http\Middleware\XSSProtectionMiddleware::class,
        'input.validation' => \App\Http\Middleware\InputValidationMiddleware::class,
        'ip.blocking' => \App\Http\Middleware\IPBlockingMiddleware::class,
    ]);

    // Apply rate limiting to web routes
    $middleware->web(append: [
        \App\Http\Middleware\RateLimitMiddleware::class . ':120,1', // 120 requests per minute for web
    ]);

    // Apply stricter rate limiting to auth routes  
    $middleware->group('auth-strict', [
        \App\Http\Middleware\RateLimitMiddleware::class . ':30,1', // 30 requests per minute for auth
    ]);

    // Owner-specific middleware group
    $middleware->group('owner', [
        'auth:owner',
        \App\Http\Middleware\RateLimitMiddleware::class . ':60,1'
    ]);

    // Admin-specific middleware group
    $middleware->group('admin', [
        'auth:admin',
        \App\Http\Middleware\RateLimitMiddleware::class . ':60,1'
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
