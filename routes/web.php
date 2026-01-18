<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/api-client', function () {
    return view('api-client');
})->name('api-client');

Route::get('/login', function () {
    // return response()->json(['message' => 'Use POST method to login.'], 405);
    return view('login');
})->name('login');
