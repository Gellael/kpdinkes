<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

// Halaman Utama (Hanya GET)
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// PROSES LOGIN (POST) - Harus diarahkan ke URL /login atau pastikan action form benar
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

// Proses Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rute Redirect Dashboard (Sama seperti sebelumnya)
Route::get('/dashboard', function () {
    if (!Auth::check()) return redirect('/');
    $role = Auth::user()->role;
    if ($role == 'admin') return redirect()->route('admin.home');
    if ($role == 'puskesmas') return redirect()->route('puskesmas.home');
    if ($role == 'ambulan') return redirect()->route('ambulan.dashboard');
    return redirect('/');
})->name('dashboard');

// ==========================================================
// PERBAIKAN KEAMANAN: ROUTE UNTUK AKSES BERKAS RAHASIA
// Wajib login (middleware 'auth') agar bisa melihat foto KTP
// ==========================================================
Route::get('/berkas-rahasia', [DashboardController::class, 'tampilkanBerkasRahasia'])
    ->name('file.rahasia')
    ->middleware('auth');

// --- GROUP RUTE: ADMINISTRATOR ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.home');
    
    // Manajemen User
    Route::get('/users', [DashboardController::class, 'adminUsers'])->name('admin.users');
    Route::post('/users/create', [DashboardController::class, 'createUser'])->name('admin.create_user');
    Route::get('/users/edit/{id}', [DashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [DashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [DashboardController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Manajemen BPJS
    Route::get('/bpjs', [DashboardController::class, 'adminBpjs'])->name('admin.bpjs');
    Route::put('/bpjs/approve/{id}', [DashboardController::class, 'approveBpjs'])->name('admin.bpjs.approve');
    Route::put('/bpjs/reject/{id}', [DashboardController::class, 'rejectBpjs'])->name('admin.bpjs.reject');
    
    // Manajemen Ambulan
    Route::get('/ambulan', [DashboardController::class, 'adminAmbulan'])->name('admin.ambulan');
    
    // PERBAIKAN: Rute ini dipindahkan ke dalam grup agar aman & terlindungi hak akses Admin
    Route::get('/ambulan/detail/{id}', [DashboardController::class, 'adminAmbulanDetail'])->name('admin.ambulan.detail');
    
    // Export Data
    Route::get('/export/{type}', [DashboardController::class, 'exportData'])->name('admin.export');
});

// --- GROUP RUTE: PUSKESMAS ---
Route::middleware(['auth', 'role:puskesmas'])->prefix('puskesmas')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'puskesmasIndex'])->name('puskesmas.home');
    Route::post('/bpjs/store', [DashboardController::class, 'storeBpjs'])->name('puskesmas.store');
});

// --- GROUP RUTE: DRIVER AMBULAN ---
Route::middleware(['auth', 'role:ambulan'])->prefix('ambulan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ambulanIndex'])->name('ambulan.dashboard');
    Route::post('/start', [DashboardController::class, 'startAmbulan'])->name('ambulan.start');
    Route::put('/finish/{id}', [DashboardController::class, 'finishAmbulan'])->name('ambulan.finish');
    Route::get('/profil', [DashboardController::class, 'ambulanProfil'])->name('ambulan.profil');
    Route::put('/profil/update', [DashboardController::class, 'updateProfilAmbulan'])->name('ambulan.profil.update');
});