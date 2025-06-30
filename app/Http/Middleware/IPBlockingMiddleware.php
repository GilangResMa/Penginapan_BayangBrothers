<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IPBlockingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIP = $request->ip();
        
        // Check if IP blocking is enabled
        if (!config('security.ip_blocking.enabled', false)) {
            return $next($request);
        }
        
        // Check if IP is in the blocked list
        $blockedIPs = config('security.ip_blocking.blocked_ips', []);
        if (in_array($clientIP, $blockedIPs)) {
            Log::warning('Blocked IP attempted access', [
                'ip' => $clientIP,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now()
            ]);
            
            return response('Access Denied', 403);
        }
        
        // Check if IP is temporarily blocked due to suspicious activity
        $blockKey = 'ip_blocked:' . $clientIP;
        if (Cache::has($blockKey)) {
            Log::warning('Temporarily blocked IP attempted access', [
                'ip' => $clientIP,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'blocked_until' => Cache::get($blockKey),
                'timestamp' => now()
            ]);
            
            return response('Temporarily blocked due to suspicious activity', 429);
        }
        
        // Check failed login attempts
        $this->checkFailedLoginAttempts($request);
        
        return $next($request);
    }
    
    /**
     * Check and handle failed login attempts
     */
    protected function checkFailedLoginAttempts(Request $request): void
    {
        $clientIP = $request->ip();
        $failedAttemptsKey = 'failed_logins:' . $clientIP;
        $maxAttempts = 5;
        $blockDuration = 15; // minutes
        
        // Only check on login routes
        if (!$request->is('actionlogin') && !$request->is('login')) {
            return;
        }
        
        $failedAttempts = Cache::get($failedAttemptsKey, 0);
        
        // If too many failed attempts, block the IP
        if ($failedAttempts >= $maxAttempts) {
            $blockKey = 'ip_blocked:' . $clientIP;
            Cache::put($blockKey, now()->addMinutes($blockDuration), now()->addMinutes($blockDuration));
            
            Log::warning('IP blocked due to excessive failed login attempts', [
                'ip' => $clientIP,
                'failed_attempts' => $failedAttempts,
                'blocked_for_minutes' => $blockDuration,
                'timestamp' => now()
            ]);
            
            // Reset failed attempts counter
            Cache::forget($failedAttemptsKey);
        }
    }
    
    /**
     * Record a failed login attempt
     */
    public static function recordFailedLogin(string $ip): void
    {
        $failedAttemptsKey = 'failed_logins:' . $ip;
        $attempts = Cache::get($failedAttemptsKey, 0) + 1;
        
        // Store for 30 minutes
        Cache::put($failedAttemptsKey, $attempts, now()->addMinutes(30));
        
        Log::info('Failed login attempt recorded', [
            'ip' => $ip,
            'total_attempts' => $attempts,
            'timestamp' => now()
        ]);
    }
    
    /**
     * Clear failed login attempts for an IP (called on successful login)
     */
    public static function clearFailedLogins(string $ip): void
    {
        $failedAttemptsKey = 'failed_logins:' . $ip;
        Cache::forget($failedAttemptsKey);
        
        Log::info('Failed login attempts cleared for IP', [
            'ip' => $ip,
            'timestamp' => now()
        ]);
    }
}
