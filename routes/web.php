<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductGalleryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/login', 'loginProcess')->name('loginProcess');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerProcess')->name('registerProcess');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transaction', TransactionController::class);

    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');

    Route::middleware(['checkrole:admin'])->group(function () {
        Route::resource('produk', ProductController::class)->except(['index']);
        Route::resource('user', UserController::class);
        Route::resource('product-gallery', ProductGalleryController::class)->except(['show']);
    });
});