<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CekDB;
use App\Http\Controllers\UserRegister;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

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
)->name('register');

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
