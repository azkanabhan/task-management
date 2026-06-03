<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/tasks', [TaskController::class, 'tasks'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/categories/create', [TaskCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [TaskCategoryController::class, 'store'])->name('categories.store');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
    Route::post('/teams/{team}/approve', [TeamController::class, 'approve'])->name('teams.approve');
    Route::post('/teams/{team}/reject', [TeamController::class, 'reject'])->name('teams.reject');
    Route::post('/teams/{team}/role', [TeamController::class, 'updateRole'])->name('teams.role.update');
    Route::post('/teams/{team}/kick', [TeamController::class, 'kick'])->name('teams.kick');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
