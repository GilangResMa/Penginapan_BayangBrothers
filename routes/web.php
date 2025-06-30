<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegister;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Homepage (arahkan user ke sini dulu)
Route::get('/', function () {
    return view('homepage');
})->name('homepage');

// Public routes (bisa diakses tanpa login)
Route::get('/room', [RoomController::class, 'index'])->name('room.index');

// API route untuk check availability
Route::post('/api/room/availability', [RoomController::class, 'checkAvailability'])->name('room.check.availability');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/faq', function () {
    $faqs = \App\Models\Faq::all();
    return view('faq', compact('faqs'));
})->name('faq');

// Auth routes - with stricter rate limiting
Route::middleware(['auth-strict'])->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login')->withoutMiddleware(['auth-strict']);
    Route::post('/actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
    Route::get('/register', [UserRegister::class, 'showRegisterForm'])->name('register')->withoutMiddleware(['auth-strict']);
    Route::post('/register', [UserRegister::class, 'register'])->name('register.store');

    // Forgot Password routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password')->withoutMiddleware(['auth-strict']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset')->withoutMiddleware(['auth-strict']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Protected routes - butuh login untuk booking
Route::middleware('auth:web')->group(function () {
    Route::post('/room/book/{id}', [RoomController::class, 'book'])->name('room.book');
    Route::get('/booking-history', [RoomController::class, 'bookingHistory'])->name('booking.history');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Payment routes
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment');
    Route::post('/payment/{booking}/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{booking}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking?}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Admin routes - with enhanced security
Route::middleware(['admin'])->group(function () {
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

// Owner routes - with enhanced security  
Route::middleware(['owner'])->group(function () {
    Route::get('/owner-dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');

    // Booking management
    Route::get('/owner/bookings', [OwnerController::class, 'bookings'])->name('owner.bookings');
    Route::get('/owner/booking/{id}', [OwnerController::class, 'showBooking'])->name('owner.booking.show');

    // Revenue analytics
    Route::get('/owner/revenue', [OwnerController::class, 'revenue'])->name('owner.revenue');
    Route::get('/owner/export/revenue', [OwnerController::class, 'exportRevenue'])->name('owner.export.revenue');

    // Admin management
    Route::get('/owner/admins', [OwnerController::class, 'admins'])->name('owner.admins');
    Route::get('/owner/admin/create', [OwnerController::class, 'createAdmin'])->name('owner.admin.create');
    Route::post('/owner/admin', [OwnerController::class, 'storeAdmin'])->name('owner.admin.store');
    Route::get('/owner/admin/{id}/edit', [OwnerController::class, 'editAdmin'])->name('owner.admin.edit');
    Route::put('/owner/admin/{id}', [OwnerController::class, 'updateAdmin'])->name('owner.admin.update');
    Route::delete('/owner/admin/{id}/delete', [OwnerController::class, 'deleteAdmin'])->name('owner.admin.delete');
});

// Debug route untuk test Midtrans config
Route::get('/test-midtrans', function () {
    try {
        // Check if classes exist
        $checks = [
            'Midtrans package installed' => class_exists('\Midtrans\Config'),
            'Midtrans Snap available' => class_exists('\Midtrans\Snap'),
            'Server key configured' => !empty(config('midtrans.server_key')),
            'Client key configured' => !empty(config('midtrans.client_key')),
        ];

        // Test basic Midtrans setup
        if (class_exists('\Midtrans\Config')) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        }

        return response()->json([
            'status' => 'success',
            'checks' => $checks,
            'config' => [
                'server_key' => config('midtrans.server_key') ? 'SET (length: ' . strlen(config('midtrans.server_key')) . ')' : 'NOT SET',
                'client_key' => config('midtrans.client_key') ? 'SET (length: ' . strlen(config('midtrans.client_key')) . ')' : 'NOT SET',
                'merchant_id' => config('midtrans.merchant_id'),
                'is_production' => config('midtrans.is_production'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.midtrans');

// Logout routes untuk semua guards
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    Auth::guard('owner')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
