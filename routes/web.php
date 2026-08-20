<?php

use App\Http\Controllers\Admin\AccessManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\MenuManagementController;
use App\Http\Controllers\PopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RectifierController;
use App\Http\Controllers\RmaController;
use Illuminate\Support\Facades\Route;

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

    Route::get('/menus', [MenuManagementController::class, 'index'])->name('admin.menus.index');
    Route::post('/menus', [MenuManagementController::class, 'store'])->name('admin.menus.store');
    Route::patch('/menus/{menu}/roles', [MenuManagementController::class, 'updateRoles'])->name('admin.menus.updateRoles');
    Route::delete('/menus/{menu}', [MenuManagementController::class, 'destroy'])->name('admin.menus.destroy');

    Route::get('/access', [AccessManagementController::class, 'index'])->name('admin.access.index');
    Route::get('/access/{role}/edit', [AccessManagementController::class, 'edit'])->name('admin.access.edit');
    Route::get('/access/{role}/permissions', [AccessManagementController::class, 'getRolePermissions'])->name('admin.access.getPermissions');
    Route::patch('/access/{role}', [AccessManagementController::class, 'update'])->name('admin.access.update');
});

Route::middleware('auth')->group(function () {

    // --- FORM RMA ---
    Route::get('/rma', function () {
        return view('rma');
    })->name('rma');
    Route::post('/rma', [RmaController::class, 'store'])->name('rma.store');
    Route::get('/rma/{id}/pdf', [RmaController::class, 'generatePdf'])->name('rma.pdf');

    // --- POP: VIEW (Semua Role: Karyawan, Teknisi, Super Admin) ---
    Route::get('/pops', [PopController::class, 'index'])->name('pops.index');
    Route::get('/pops/{id}', [PopController::class, 'show'])->name('pops.show');

    // --- POP: EDIT (Teknisi & Super Admin) ---
    Route::middleware('role:super_admin|teknisi')->group(function () {
        Route::get('/pops/{id}/edit', [PopController::class, 'edit'])->name('pops.edit');
        Route::put('/pops/{id}', [PopController::class, 'update'])->name('pops.update');
    });

    // --- POP: CREATE & DELETE (Hanya Super Admin) ---
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/pops/create', [PopController::class, 'create'])->name('pops.create');
        Route::post('/pops', [PopController::class, 'store'])->name('pops.store');
        Route::delete('/pops/{id}', [PopController::class, 'destroy'])->name('pops.destroy');
    });

    // --- RECTIFIERS ---
    Route::get('/pops/{pop}/rectifiers', [RectifierController::class, 'index']);      
    Route::get('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'show']);  
    
    Route::middleware('role:super_admin,teknisi')->group(function () {
        Route::put('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'update']);
    });
    
    Route::middleware('role:super_admin')->group(function () {
        Route::post('/pops/{pop}/rectifiers', [RectifierController::class, 'store']);     
        Route::delete('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'destroy']); 
    });

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

Route::get('/rma-awal', function () {
    return view('rma-awal');
})->name('rma.index');

