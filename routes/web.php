<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TrainerPageController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingTransactionController;
use App\Http\Controllers\TrainorController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController:: class, 'handler'] )->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');
});

// , 'verified'
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/scan', [LogController::class, 'scan'])->name('scan');
    Route::post('/scan', [LogController::class, 'scanFullScreen'])->name('scanFullScreen');
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    //2factor in verify becos how can you send email if not vefied utok ba 
    Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two-factor.index');
    Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::post('/two-factor/resend', [TwoFactorController::class, 'resendCode'])->name('two-factor.resend');
});

Route::middleware(['auth', 'role:admin|user|trainor'])->group(function () {
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::put('/change-password/{user}', [UserController::class, 'updatePassword'])->name('updatePassword');
});

Route::middleware(['auth', 'role:admin|user'])->group(function () {
    Route::get('/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/edit/{user}', [UserController::class, 'editProfile'])->name('editProfile');
});

// , 'verified', 'twofactor'

Route::middleware(['auth', 'role:admin'])->group(function () {
    //dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    //register
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    //create users
    Route::get('/users',[UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/editUsername',[UserController::class, 'view'])->name('users.view');
    Route::get('/users/{user}/forgotPass',[UserController::class, 'forgotPassword'])->name('users.forgotPassword');
    Route::post('/users',[UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}',[UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/updateUsername',[UserController::class, 'updateUsername'])->name('users.updateUsername');
    Route::put('/users/{user}/updateForgotPassword',[UserController::class, 'updateFrogotPass'])->name('users.updateForgotPassword');
    Route::delete('/users/{user}',[UserController::class, 'destroy'])->name('users.destroy');
});

// ,  'verified', 'twofactor'
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::post('/clients',[ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'view'])->name('clients.view');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{client}', [ClientController::class, 'updatePayment'])->name('clients.updatePayment');
    Route::delete('/clients/{client}',[ClientController::class, 'destroy'])->name('clients.destroy');
    Route::put('/clients/{client}/becomeMember', [ClientController::class, 'becomeMember'])->name('clients.becomeMember');
    Route::put('/clients/{client}/revokeMembership', [ClientController::class, 'revoke'])->name('clients.revoke');

    // Logs 
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/list', [LogController::class, 'list'])->name('logs.list');
    Route::post('/logs', [LogController::class, 'store'])->name('logs.store');
    Route::post('/logs/manual', [LogController::class, 'manualStore'])->name('logs.manualStore');
    Route::delete('/clientLogs/{log}',[ClientController::class, 'destroyLog'])->name('clientLogs.destroy');
    Route::delete('/logs/{log}',[LogController::class, 'destroy'])->name('logs.destroy');
    // Pending Clients
    Route::get('/pending', [LogController::class, 'pending'])->name('pending');

    // Client Transaction
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'createTransaction'])->name('transactions.create');
    Route::post('/transactionAndLog', [TransactionController::class, 'createTransactionWithLogs'])->name('transactions.createWithLog');
    Route::delete('/transaction/{transaction}',[TransactionController::class, 'destroy'])->name('transactions.destroy');
    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    // Coaches
    Route::get('/coaches', [CoachController::class, 'index'])->name('coaches.index');
    Route::get('/coaches/{coach}', [CoachController::class, 'view'])->name('coaches.view');
    //Coaches Store and update for admin and coach
    Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
    Route::put('/coaches/{coach}', [CoachController::class, 'update'])->name('coaches.update');
    Route::delete('/coaches/{coach}', [CoachController::class, 'destroy'])->name('coaches.destroy');

    // Trainings
    Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('/trainings/{training}', [TrainingController::class, 'view'])->name('trainings.view');
    //storing, updating and destroying trainings
    Route::post('/trainings', [TrainingController::class, 'store'])->name('trainings.store');
    Route::put('/trainings/{training}', [TrainingController::class, 'update'])->name('trainings.update');
    Route::delete('/trainings/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');
    Route::put('/trainings/{training}/add', [TrainingController::class, 'addClientToTraining'])->name('trainings.addClient');
    Route::put('/trainings/{training}/remove', [TrainingController::class, 'removeClientFromTraining'])->name('trainings.removeClient');
    Route::delete('/clientTransaction/{transaction}/', [ClientController::class, 'destroyTransaction'])->name('clientTransactions.destroy');

    //Training Transactions
    Route::get('/trainingTransactions', [TrainingTransactionController::class, 'index'])->name('trainingTransactions.index');
    Route::delete('/trainingTransactions/{trainingTransaction}', [TrainingTransactionController::class, 'destroy'])->name('trainingTransactions.destroy');

    //ToDo list controlls
    Route::post('/toDoList', [TodoListController::class, 'store'])->name('toDoList.store');
    Route::put('toDoList/{todo}', [TodoListController::class, 'update'])->name('toDoList.update');
    Route::delete('toDoList/{todo}', [TodoListController::class, 'destroy'])->name('toDoList.destroy');
});


Route::middleware(['auth', 'role:trainor'])->group(function () {
    Route::get('/editCoach', [UserController::class, 'editCoach'])->name('editCoach');
    Route::put('/editCoach/{user}', [UserController::class, 'editCoachProfile'])->name('editCoachProfile');
    Route::get('/trainerDashboard', [TrainerPageController::class, 'trainerDashboard'])->name('trainerDashboard');
    //Training list
    Route::get('/trainingList', [TrainerPageController::class, 'trainingList'])->name('trainingList');
    Route::post('/trainingList', [TrainerPageController::class, 'trainingStore'])->name('trainingList.store');
    Route::get('/trainingList/{training}', [TrainerPageController::class, 'view'])->name('trainingList.view');
    Route::put('/trainingList/{training}', [TrainerPageController::class, 'trainingUpdate'])->name('trainingList.update');
    Route::delete('/trainingList/{training}', [TrainerPageController::class, 'trainingDestroy'])->name('trainingList.destroy');
    Route::put('/trainingList/{training}/add', [TrainerPageController::class, 'addClientToTraining'])->name('trainingList.addClient');
    Route::put('/trainingList/{training}/remove', [TrainerPageController::class, 'removeClientFromTraining'])->name('trainingList.removeClient');
    
    //trainer clients to do list managemente
    Route::get('/trainingList/clientToDoList/{client}', [TrainerPageController::class, 'clientToDoList'])->name('clientToDoList');
    Route::post('/trainerClientToDoList', [TrainerPageController::class, 'toDoStore'])->name('trainerClientToDoList.store');
    Route::put('/trainerClientToDoList/{todo}', [TrainerPageController::class, 'toDoUpdate'])->name('trainerClientToDoList.update');
    Route::delete('trainerClientToDoList/{todo}', [TrainerPageController::class, 'toDoDestroy'])->name('trainerClientToDoList.destroy');
});


Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/userDashboard', [UserPageController::class, 'userDashboard'])->name('userDashboard');
    Route::get('/userToDoList', [UserPageController::class, 'userToDoList'])->name('userToDoList');
    Route::post('/userToDoList', [UserPageController::class, 'store'])->name('userToDoList.store');
    Route::put('/userToDoList/{todo}', [UserPageController::class, 'update'])->name('userToDoList.update');
    Route::delete('userToDoList/{todo}', [UserPageController::class, 'destroy'])->name('userToDoList.destroy');

});
