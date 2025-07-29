<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Resource routes - these create all CRUD routes automatically
    Route::resource('customers', CustomerController::class);
    Route::resource('proposals', ProposalController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
    
    // Invoice specific routes
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
});

// Payment routes (accessible without authentication for customers)
Route::get('payment/{invoice}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('payment/{invoice}/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';