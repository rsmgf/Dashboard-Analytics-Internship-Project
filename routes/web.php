<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('auth.login');
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