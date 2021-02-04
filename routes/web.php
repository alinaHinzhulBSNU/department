<?php

use Illuminate\Support\Facades\Route;

//Auth
Auth::routes();

//Home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
