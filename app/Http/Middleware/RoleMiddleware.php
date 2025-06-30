<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated with the correct guard
        switch ($role) {
            case 'admin':
                if (!Auth::guard('admin')->check()) {
                    return redirect()->route('login')->with('error', 'Unauthorized access. Admin login required.');
                }
                break;
                
            case 'owner':
                if (!Auth::guard('owner')->check()) {
                    return redirect()->route('login')->with('error', 'Unauthorized access. Owner login required.');
                }
                break;
                
            case 'user':
                if (!Auth::guard('web')->check()) {
                    return redirect()->route('login')->with('error', 'Please login to continue.');
                }
                break;
                
            default:
                return redirect()->route('login')->with('error', 'Invalid role specified.');
        }
        
        return $next($request);
    }
}
