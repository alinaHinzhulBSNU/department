<?php

use Illuminate\Support\Facades\Route;

//Auth
Auth::routes();

//Home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin users
Route::get('/users/search', [\App\Http\Controllers\UsersController::class, 'search']);
Route::resource('/users', "\App\Http\Controllers\UsersController");

//Teachers
Route::resource('/teachers', "\App\Http\Controllers\TeachersController");

//Subjects
Route::resource('/subjects', "\App\Http\Controllers\SubjectsController");

//Groups
Route::get('/groups/search', [\App\Http\Controllers\GroupsController::class, 'search']);
Route::resource('/groups', "\App\Http\Controllers\GroupsController");

//Students
Route::get('/students/search', [\App\Http\Controllers\StudentsController::class, 'search']);
Route::resource('/students', "\App\Http\Controllers\StudentsController");