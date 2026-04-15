<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    if (!Auth::check()) return redirect('/');
    
    $role = Auth::user()->role;
    if ($role == 'admin') return redirect()->route('admin.home');
    if ($role == 'puskesmas') return redirect()->route('puskesmas.home');
    if ($role == 'ambulan') return redirect()->route('ambulan.dashboard');
    
    return redirect('/');
})->name('dashboard');


Route::get('/berkas-rahasia', [DashboardController::class, 'tampilkanBerkasRahasia'])
    ->name('file.rahasia')
    ->middleware('auth');


// ==========================================================
// GROUP RUTE: ADMINISTRATOR
// ==========================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.home');
    Route::get('/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
    Route::post('/users/create', [DashboardController::class, 'createUser'])->name('admin.create_user');
    Route::get('/users/edit/{id}', [DashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [DashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [DashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/bpjs', [DashboardController::class, 'adminBpjs'])->name('admin.bpjs');
    Route::put('/bpjs/approve/{id}', [DashboardController::class, 'approveBpjs'])->name('admin.bpjs.approve');
    Route::put('/bpjs/reject/{id}', [DashboardController::class, 'rejectBpjs'])->name('admin.bpjs.reject');
    Route::get('/ambulan', [DashboardController::class, 'adminAmbulan'])->name('admin.ambulan');
    Route::get('/ambulan/detail/{id}', [DashboardController::class, 'adminAmbulanDetail'])->name('admin.ambulan.detail');
    Route::delete('/ambulan/delete/{id}', [DashboardController::class, 'destroyAmbulan'])->name('admin.ambulan.destroy');
    Route::get('/export/{type}', [DashboardController::class, 'exportData'])->name('admin.export');
});


// ==========================================================
// GROUP RUTE: PUSKESMAS
// ==========================================================
Route::middleware(['auth', 'role:puskesmas'])->prefix('puskesmas')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'puskesmasIndex'])->name('puskesmas.home');
    Route::post('/bpjs/store', [DashboardController::class, 'storeBpjs'])->name('puskesmas.store');
});


// ==========================================================
// GROUP RUTE: DRIVER AMBULAN
// ==========================================================
Route::middleware(['auth', 'role:ambulan'])->prefix('ambulan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ambulanIndex'])->name('ambulan.dashboard');
    Route::post('/start', [DashboardController::class, 'startAmbulan'])->name('ambulan.start');
    Route::put('/finish/{id}', [DashboardController::class, 'finishAmbulan'])->name('ambulan.finish');
    
    // Profil Driver
    Route::get('/profil', [DashboardController::class, 'ambulanProfil'])->name('ambulan.profil');
    Route::put('/profil/update', [DashboardController::class, 'updateProfilAmbulan'])->name('ambulan.profil.update');
});