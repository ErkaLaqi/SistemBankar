<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountTransactionImportController;
use App\Http\Controllers\AccountTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;


Route::middleware(['auth'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/import', [AccountTransactionImportController::class, 'show'])->name('accounts.show');
    Route::post('/accounts/import', [AccountTransactionImportController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{account}/transactions', [AccountTransactionController::class, 'getTransactions'])->name('accounts.transactions');

    Route::get('accounts/transfer', [AccountController::class, 'showTransferForm'])->name('accounts.showTransferForm');
    Route::post('accounts/transfer', [AccountController::class, 'transfer'])->name('accounts.transfer');
});



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
