<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ImpersonateController;

Route::get('/', function () { return view('welcome'); });

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    Route::get('/drive', [DriveController::class, 'index'])->name('drive.index');
    Route::post('/drive/upload', [DriveController::class, 'upload'])->name('drive.upload');

    Route::get('/google/connect', [DriveController::class, 'connect'])->name('google.connect');
    Route::get('/google/callback', [DriveController::class, 'callback'])->name('google.callback');

    Route::get('/impersonate/{user}', [ImpersonateController::class, 'start'])->middleware('can:impersonate')->name('impersonate.start');
    Route::get('/impersonate/stop', [ImpersonateController::class, 'stop'])->name('impersonate.stop');
});

Route::middleware(['auth', 'can:admin-area'])->group(function () {
    Route::get('/admin', [UserAdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/toggle/{user}', [UserAdminController::class, 'toggle'])->name('admin.toggle');
    Route::post('/admin/role/{user}', [UserAdminController::class, 'role'])->name('admin.role');
    Route::post('/admin/quota/{user}', [UserAdminController::class, 'quota'])->name('admin.quota');
});
