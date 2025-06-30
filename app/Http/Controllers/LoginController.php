<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\IPBlockingMiddleware;

class LoginController extends Controller
{
    // Method untuk menampilkan form login
    public function login()
    {
        return view('login');
    }

    public function actionlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $clientIP = $request->ip();

        // Coba login sebagai owner terlebih dahulu
        if (Auth::guard('owner')->attempt($credentials)) {
            $request->session()->regenerate();

            // Clear failed login attempts on successful login
            IPBlockingMiddleware::clearFailedLogins($clientIP);

            Log::info('Owner login successful', [
                'email' => $credentials['email'],
                'ip' => $clientIP,
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            return redirect()->route('owner.dashboard');
        }

        // Coba login sebagai admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            // Clear failed login attempts on successful login
            IPBlockingMiddleware::clearFailedLogins($clientIP);

            Log::info('Admin login successful', [
                'email' => $credentials['email'],
                'ip' => $clientIP,
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            return redirect()->route('admin.dashboard');
        }

        // Coba login sebagai user
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            // Clear failed login attempts on successful login
            IPBlockingMiddleware::clearFailedLogins($clientIP);

            Log::info('User login successful', [
                'email' => $credentials['email'],
                'ip' => $clientIP,
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            return redirect()->route('profile');
        }

        // Record failed login attempt
        IPBlockingMiddleware::recordFailedLogin($clientIP);

        Log::warning('Failed login attempt', [
            'email' => $credentials['email'],
            'ip' => $clientIP,
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ])->withInput($request->only('email'));
    }
}