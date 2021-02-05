<?php

use Illuminate\Support\Facades\Route;

//Auth
Auth::routes();

//Home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin users
Route::resource('/users', "\App\Http\Controllers\UsersController");

//User search
Route::get('/search', [\App\Http\Controllers\UsersController::class, 'search']);