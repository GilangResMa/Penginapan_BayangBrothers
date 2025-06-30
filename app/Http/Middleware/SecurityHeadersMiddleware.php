<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // X-Content-Type-Options: Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // X-Frame-Options: Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // X-XSS-Protection: Enable XSS filtering
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer Policy: Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy: Prevent XSS and data injection attacks
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://app.midtrans.com https://app.stg.midtrans.com",
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: http:",
            "font-src 'self' https://cdnjs.cloudflare.com",
            "connect-src 'self' https://api.midtrans.com https://api.stg.midtrans.com",
            "frame-src 'self' https://app.midtrans.com https://app.stg.midtrans.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        
        // Strict Transport Security: Force HTTPS (only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Permissions Policy: Control browser features
        $permissionsPolicy = [
            'camera=()',
            'microphone=()',
            'geolocation=()',
            'interest-cohort=()',
            'payment=(self)',
            'usb=()',
            'magnetometer=()',
            'accelerometer=()',
            'gyroscope=()'
        ];
        $response->headers->set('Permissions-Policy', implode(', ', $permissionsPolicy));
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
        
        return $response;
    }
}
