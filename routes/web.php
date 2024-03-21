<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

Route::get('/', [HomeController::class, 'index']);

Route::resource('posts', PostController::class);
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');




Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('auth');
Route::get('/register', [LoginController::class, 'register'])->name('register')->middleware('auth', 'passwords.confirm');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Auth::routes();
