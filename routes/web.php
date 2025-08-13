<?php

use App\Http\Controllers\PresenceController;
use App\Http\Controllers\PresenceScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

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
    // Worker management routes
    Route::post('/workers/{worker}/deactivate', [WorkerController::class, 'deactivate'])->name('workers.deactivate');
    Route::get('/workers/inactive', [WorkerController::class, 'inactive'])->name('workers.inactive');
    Route::post('/workers/{worker}/activate', [WorkerController::class, 'activate'])->name('workers.activate');
    Route::resource('workers', WorkerController::class);
    // Presence management routes
    Route::post('presences/scan', [PresenceController::class, 'scanQr'])->name('presences.scan');
    Route::resource('presences', PresenceController::class);
    Route::resource('presence-schedules', PresenceScheduleController::class)->except(['create', 'edit']);
    Route::get('presence-schedules', [PresenceScheduleController::class, 'index'])->name('presence-schedules.index');
    Route::post('presence-schedules', [PresenceScheduleController::class, 'storeOrUpdate'])->name('presence-schedules.save');
});

require __DIR__ . '/auth.php';
