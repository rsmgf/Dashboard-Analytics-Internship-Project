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