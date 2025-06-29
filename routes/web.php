<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegister;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
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
    $faqs = \App\Models\Faq::all();
    return view('faq', compact('faqs'));
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

    // Payment routes
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
});

// Midtrans notification (tidak perlu auth)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// Admin routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Room management routes
    Route::get('/admin/rooms', [AdminController::class, 'roomIndex'])->name('admin.rooms.index');
    Route::get('/admin/rooms/create', [AdminController::class, 'roomCreate'])->name('admin.rooms.create');
    Route::post('/admin/rooms', [AdminController::class, 'roomStore'])->name('admin.rooms.store');
    Route::get('/admin/rooms/{id}/edit', [AdminController::class, 'roomEdit'])->name('admin.rooms.edit');
    Route::put('/admin/rooms/{id}', [AdminController::class, 'roomUpdate'])->name('admin.rooms.update');
    Route::delete('/admin/rooms/{id}', [AdminController::class, 'roomDestroy'])->name('admin.rooms.destroy');

    // FAQ management routes
    Route::get('/admin/faqs', [AdminController::class, 'faqIndex'])->name('admin.faqs.index');
    Route::get('/admin/faqs/create', [AdminController::class, 'faqCreate'])->name('admin.faqs.create');
    Route::post('/admin/faqs', [AdminController::class, 'faqStore'])->name('admin.faqs.store');
    Route::get('/admin/faqs/{id}/edit', [AdminController::class, 'faqEdit'])->name('admin.faqs.edit');
    Route::put('/admin/faqs/{id}', [AdminController::class, 'faqUpdate'])->name('admin.faqs.update');
    Route::delete('/admin/faqs/{id}', [AdminController::class, 'faqDestroy'])->name('admin.faqs.destroy');
});

// Logout routes untuk kedua guards
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
