<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TrainerPageController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingTransactionController;
use App\Http\Controllers\TrainorController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin|user|trainor'])->group(function () {
    Route::get('/edit', [UserController::class, 'edit'])->name('edit');
    Route::get('/editCoach', [UserController::class, 'editCoach'])->name('editCoach');
    Route::put('/edit/{user}', [UserController::class, 'editProfile'])->name('editProfile');
    Route::put('/editCoach/{user}', [UserController::class, 'editCoachProfile'])->name('editCoachProfile');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::put('/change-password/{user}', [UserController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/toDoList', [TodoListController::class, 'store'])->name('toDoList.store');
    Route::put('toDoList/{todo}', [TodoListController::class, 'update'])->name('toDoList.update');
});

Route::middleware(['auth', 'role:trainor'])->group(function () {
    Route::get('/trainerDashboard', [TrainerPageController::class, 'trainerDashboard'])->name('trainerDashboard');
    Route::get('/trainingList', [TrainerPageController::class, 'trainingList'])->name('trainingList');

});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/userDashboard', [UserPageController::class, 'userDashboard'])->name('userDashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users',[UserController::class, 'index'])->name('users');
    Route::post('/users',[UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}',[UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',[UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth', 'role:admin')->group(function () {
    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::post('/clients',[ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'view'])->name('clients.view');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{client}', [ClientController::class, 'updatePayment'])->name('clients.updatePayment');
    Route::delete('/clients/{client}',[ClientController::class, 'destroy'])->name('clients.destroy');
    Route::delete('/transaction/{transaction}',[ClientController::class, 'destroyTransaction'])->name('clientTransactions.destroy');
    // Logs 
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/list', [LogController::class, 'list'])->name('logs.list');
    Route::post('/logs', [LogController::class, 'store'])->name('logs.store');
    Route::post('/logs/manual', [LogController::class, 'manualStore'])->name('logs.manualStore');
    Route::delete('/log/{log}',[ClientController::class, 'destroyLog'])->name('clientLogs.destroy');
    Route::delete('/logs/{log}',[LogController::class, 'destroy'])->name('logs.destroy');
    // Pending Clients
    Route::get('/pending', [LogController::class, 'pending'])->name('pending');
    // Client Transaction
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'createTransaction'])->name('transactions.create');
    Route::post('/transactionAndLog', [TransactionController::class, 'createTransactionWithLogs'])->name('transactions.createWithLog');
    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'delete'])->name('announcements.delete');
});

Route::middleware('auth')->group(function () {
    // Coaches
    Route::get('/coaches', [CoachController::class, 'index'])->name('coaches.index');
    Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
    Route::put('/coaches/{coach}', [CoachController::class, 'update'])->name('coaches.update');
    // Trainings
    Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::post('/trainings', [TrainingController::class, 'store'])->name('trainings.store');
    Route::get('/trainings/{training}', [TrainingController::class, 'view'])->name('trainings.view');
    Route::put('/trainings/{training}', [TrainingController::class, 'update'])->name('trainings.update');
    Route::put('/trainings/{training}/add', [TrainingController::class, 'addClientToTraining'])->name('trainings.addClient');
    Route::put('/trainings/{training}/remove', [TrainingController::class, 'removeClientFromTraining'])->name('trainings.removeClient');
    Route::delete('/trainings/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/trainingTransactions', [TrainingTransactionController::class, 'index'])->name('trainingTransactions.index');
    Route::delete('/trainingTransactions/{trainingTransaction}', [TrainingTransactionController::class, 'destroy'])->name('trainingTransactions.destroy');
});
