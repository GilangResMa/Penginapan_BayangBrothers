<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegister;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Homepage (arahkan user ke sini dulu)
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Public routes (bisa diakses tanpa login)
Route::get('/room', [RoomController::class, 'index'])->name('room.index');
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Auth routes
Route::get('/register', [UserRegister::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserRegister::class, 'register'])->name('register.store');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

// Protected routes - butuh login untuk booking
Route::middleware('auth:web')->group(function () {
    Route::post('/room/book/{id}', [RoomController::class, 'book'])->name('room.book');
    Route::get('/booking-history', [RoomController::class, 'bookingHistory'])->name('booking.history');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Admin routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Logout routes untuk kedua guards
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
