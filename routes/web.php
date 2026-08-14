<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RmaController;
use App\Http\Controllers\PopController;
use App\Http\Controllers\RectifierController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('auth.login');
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

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
});

Route::get('/reset1', function () {
    return view('auth.reset1');
});

Route::get('/reset2', function () {
    return view('auth.reset2');
});

Route::get('/reset3', function () {
    return view('auth.reset3');
});

Route::get('/rma', function () {
    return view('rma');
})->name('rma');

// Rute untuk menangani saat tombol "Simpan" diklik
Route::post('/rma', [RmaController::class, 'store'])->name('rma.store');

// Rute untuk mencetak PDF
Route::get('/rma/{id}/pdf', [RmaController::class, 'generatePdf'])->name('rma.pdf');

Route::get('/pop', function () {
    return view('list-pop');
})->name('pop');


// 1. Menampilkan daftar semua POP
Route::get('/pops', [PopController::class, 'index']);      // Lihat semua POP
Route::post('/pops', [PopController::class, 'store']);     // Tambah POP baru
Route::get('/pops/{id}', [PopController::class, 'show']);  // Lihat detail 1 POP
Route::put('/pops/{id}', [PopController::class, 'update']); // Update POP
Route::delete('/pops/{id}', [PopController::class, 'destroy']); // Hapus POP

Route::get('/pops/{pop}/rectifiers', [RectifierController::class, 'index']);      // Lihat semua perangkat di 1 POP
Route::post('/pops/{pop}/rectifiers', [RectifierController::class, 'store']);     // Tambah perangkat baru di 1 POP
Route::get('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'show']);  // Lihat detail 1 perangkat
// ... rute rectifier sebelumnya ...
Route::put('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'update']); // Update data
Route::delete('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'destroy']); // Hapus data
