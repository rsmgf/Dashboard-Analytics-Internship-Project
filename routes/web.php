<?php

use App\Http\Controllers\Admin\AccessManagementController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\KwhController;
use App\Http\Controllers\PopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RectifierController;
use App\Http\Controllers\RmaController;
use Illuminate\Support\Facades\Route;

// --- GUEST / AUTH REDIRECT ---
Route::get('/', function () {
    return view('auth.login');
});

// --- DASHBOARD ---
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- PROFILE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// --- SUPER ADMIN: USER & MENU MANAGEMENT ---
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('admin.users.toggleStatus');

    Route::get('/menus', [MenuManagementController::class, 'index'])->name('admin.menus.index');
    Route::post('/menus', [MenuManagementController::class, 'store'])->name('admin.menus.store');
    Route::patch('/menus/{menu}/roles', [MenuManagementController::class, 'updateRoles'])->name('admin.menus.updateRoles');
    Route::delete('/menus/{menu}', [MenuManagementController::class, 'destroy'])->name('admin.menus.destroy');

    Route::get('/access', [AccessManagementController::class, 'index'])->name('admin.access.index');
    Route::get('/access/{role}/edit', [AccessManagementController::class, 'edit'])->name('admin.access.edit');
    Route::get('/access/{role}/permissions', [AccessManagementController::class, 'getRolePermissions'])->name('admin.access.getPermissions');
    Route::patch('/access/{role}', [AccessManagementController::class, 'update'])->name('admin.access.update');
});

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {

    // --- FORM & RIWAYAT RMA ---
    Route::middleware('permission:rma.read')->group(function () {
        Route::get('/rma', [RmaController::class, 'index'])->name('rma');
        Route::get('/rma/{id}/download', [RmaController::class, 'downloadPdf'])->name('rma.download');
        Route::get('/rma/{id}/pdf', [RmaController::class, 'generatePdf'])->name('rma.pdf');
    });

    Route::middleware('permission:rma.create')->group(function () {
        Route::get('/rma/create', [RmaController::class, 'create'])->name('rma.create');
        Route::post('/rma', [RmaController::class, 'store'])->name('rma.store');
    });

    // --- POP: VIEW (Semua role, cukup login) ---
    Route::get('/pops', [PopController::class, 'index'])->name('pops.index');

    // --- POP: CREATE — harus SEBELUM /pops/{id} agar 'create' tidak ditangkap sebagai id ---
    Route::middleware('permission:pops.index.create')->group(function () {
        Route::get('/pops/create', [PopController::class, 'create'])->name('pops.create');
        Route::post('/pops', [PopController::class, 'store'])->name('pops.store');
    });

    // --- POP: IMPORT EXCEL — harus SEBELUM /pops/{id} ---
    Route::middleware('permission:pops.index.create')->group(function () {
        Route::get('/pops/import', [PopController::class, 'importForm'])->name('pops.import');
        Route::post('/pops/import', [PopController::class, 'import'])->name('pops.import.store');
    });

    // --- POP: SHOW (detail satu POP) ---
    Route::get('/pops/{id}', [PopController::class, 'show'])->name('pops.show');

    // --- POP: EDIT ---
    Route::middleware('permission:pops.index.update')->group(function () {
        Route::get('/pops/{id}/edit', [PopController::class, 'edit'])->name('pops.edit');
        Route::put('/pops/{id}', [PopController::class, 'update'])->name('pops.update');
    });

    // --- POP: DELETE ---
    Route::middleware('permission:pops.index.delete')->group(function () {
        Route::delete('/pops/{id}', [PopController::class, 'destroy'])->name('pops.destroy');
    });

    // --- RECTIFIERS ---
    Route::get('/pops/{pop}/rectifiers', [RectifierController::class, 'index'])->name('rectifiers.index');

    // Create harus SEBELUM /{id} agar 'create' tidak ditangkap sebagai id
    Route::middleware('permission:rectifiers.index.create')->group(function () {
        Route::get('/pops/{pop}/rectifiers/create', [RectifierController::class, 'create'])->name('rectifiers.create');
        Route::post('/pops/{pop}/rectifiers', [RectifierController::class, 'store'])->name('rectifiers.store');
    });

    Route::get('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'show'])->name('rectifiers.show');

    Route::middleware('permission:rectifiers.index.update')->group(function () {
        Route::get('/pops/{pop}/rectifiers/{id}/edit', [RectifierController::class, 'edit'])->name('rectifiers.edit');
        Route::put('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'update'])->name('rectifiers.update');
    });

    Route::middleware('permission:rectifiers.index.delete')->group(function () {
        Route::delete('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'destroy'])->name('rectifiers.destroy');
    });

    // --- KWH ---
    Route::get('/pops/{pop}/kwh', [KwhController::class, 'index'])->name('kwh.card');

    // Create HARUS sebelum /{id} agar 'create' tidak ditangkap sebagai id
    Route::middleware('permission:kwh.card.create')->group(function () {
        Route::get('/pops/{pop}/kwh/create', [KwhController::class, 'create'])->name('kwh.create');
        Route::post('/pops/{pop}/kwh', [KwhController::class, 'store'])->name('kwh.store');
    });

    Route::get('/pops/{pop}/kwh/{id}', [KwhController::class, 'show'])->name('kwh.detail');

    Route::middleware('permission:kwh.card.update')->group(function () {
        Route::get('/pops/{pop}/kwh/{id}/edit', [KwhController::class, 'edit'])->name('kwh.edit');
        Route::put('/pops/{pop}/kwh/{id}', [KwhController::class, 'update'])->name('kwh.update');
    });

    Route::middleware('permission:kwh.card.delete')->group(function () {
        Route::delete('/pops/{pop}/kwh/{id}', [KwhController::class, 'destroy'])->name('kwh.destroy');
    });
});
