<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Coba login sebagai user (menggunakan bcrypt)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/user-dashboard');
        }

        // Manual check untuk admin (tanpa hash)
        $admin = \App\Models\Admin::where('email', $credentials['email'])->first();
        if ($admin && $admin->password === $credentials['password']) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->intended('/admin-dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak valid.',
        ]);
    }
}