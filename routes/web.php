<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',[UserController::class, 'index'])->name('users');
    Route::post('/users',[UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}',[UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',[UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::post('/clients',[ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'view'])->name('clients.view');
    Route::post('/clients/{client}', [ClientController::class, 'updatePayment'])->name('clients.updatePayment');
    Route::delete('/clients/{client}',[ClientController::class, 'destroy'])->name('clients.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/list', [LogController::class, 'list'])->name('logs.list');
    Route::post('/logs', [LogController::class, 'store'])->name('logs.store');
    Route::post('/logs/manual', [LogController::class, 'manualStore'])->name('logs.manualStore');
    Route::delete('/logs/{log}',[LogController::class, 'destroy'])->name('logs.destroy');
    Route::get('/pending', [LogController::class, 'pending'])->name('pending');

});

Route::middleware('auth')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'createTransaction'])->name('transactions.create');
    Route::post('/transactionAndLog', [TransactionController::class, 'createTransactionWithLogs'])->name('transactions.createWithLog');

});

    