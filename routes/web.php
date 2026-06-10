<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokuCallbackController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportEntryController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Doku callback (public, no auth required)
Route::post('/doku/callback', [DokuCallbackController::class, 'handle'])->name('doku.callback');
Route::get('/doku/return', [CheckoutController::class, 'returnFromDoku'])->name('doku.return');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    Route::post('/tracking', [TrackingController::class, 'track'])->name('tracking.track');

    Route::resource('categories', CategoryController::class)->middleware('role:admin');
    Route::resource('items', ItemController::class)->middleware('role:admin,petugas');

    Route::get('/transactions', [StockTransactionController::class, 'index'])->name('transactions.index')->middleware('role:admin,petugas');
    Route::get('/transactions/{type}/create', [StockTransactionController::class, 'create'])->name('transactions.create')->middleware('role:admin,petugas');
    Route::post('/transactions', [StockTransactionController::class, 'store'])->name('transactions.store')->middleware('role:admin,petugas');

    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock')->middleware('role:admin,petugas');
    Route::get('/reports/stock/pdf', [ReportController::class, 'stockPdf'])->name('reports.stock.pdf')->middleware('role:admin,petugas');
    Route::get('/reports/transactions/{type}', [ReportController::class, 'transactions'])->name('reports.transactions')->middleware('role:admin,petugas');
    Route::get('/reports/transactions/{type}/pdf', [ReportController::class, 'transactionsPdf'])->name('reports.transactions.pdf')->middleware('role:admin,petugas');
    Route::resource('report-entries', ReportEntryController::class)->middleware('role:admin,petugas');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('role:admin,petugas');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read')->middleware('role:admin,petugas');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index')->middleware('role:admin,petugas');
    Route::post('/chat', [ChatController::class, 'start'])->name('chat.start')->middleware('role:admin,petugas');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show')->middleware('role:admin,petugas');
    Route::post('/chat/{conversation}', [ChatController::class, 'store'])->name('chat.store')->middleware('role:admin,petugas');
});
