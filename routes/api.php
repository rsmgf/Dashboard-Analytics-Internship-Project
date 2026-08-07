<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RmaController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Jalur untuk FE mengirim data form (metode POST)
Route::post('/rma', [RmaController::class, 'store']);

// Jalur untuk melihat/mendownload PDF (metode GET)
Route::get('/rma/{id}/pdf', [RmaController::class, 'generatePdf']);

use App\Http\Controllers\PopController;
use App\Http\Controllers\RectifierController;

// 1. Menampilkan daftar semua POP
Route::get('/pops', [PopController::class, 'index']);      // Lihat semua POP
Route::post('/pops', [PopController::class, 'store']);     // Tambah POP baru
Route::get('/pops/{id}', [PopController::class, 'show']);  // Lihat detail 1 POP
Route::delete('/pops/{id}', [PopController::class, 'destroy']); // Hapus POP
Route::put('/pops/{id}', [PopController::class, 'update']); // Update POP

Route::get('/pops/{pop}/rectifiers', [RectifierController::class, 'index']);      // Lihat semua perangkat di 1 POP
Route::post('/pops/{pop}/rectifiers', [RectifierController::class, 'store']);     // Tambah perangkat baru di 1 POP
Route::get('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'show']);  // Lihat detail 1 perangkat
// ... rute rectifier sebelumnya ...
Route::put('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'update']); // Update data
Route::delete('/pops/{pop}/rectifiers/{id}', [RectifierController::class, 'destroy']); // Hapus data