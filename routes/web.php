<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegister;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get(
    '/',
    [UserRegister::class, 'showRegisterForm']
)->name('register');

// Route::get('/login', function () {
//     return view('login');
// });

// Route::get('/register', function () {
//     return view('register');
// });

Route::get('/room', function () {
    return view('room');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::post(
    '/register',
    [UserRegister::class, 'register']
)->name('register.store');

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

// Logout routes untuk kedua guards
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Protected routes
Route::middleware('auth:web')->group(function () {
    Route::get('/user-dashboard', function () {
        return view('user.dashboard');
    });
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard');
    });
});
