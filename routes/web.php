<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;

Route::get('/', [HomeController::class, 'index']);

Route::resource('posts', PostController::class);
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/comments', [CommentController::class, 'show'])->name('show')->middleware('auth');
Route::resource('comments', CommentController::class);
Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
Route::delete('/posts/{post}/unlike', 'PostLikeController@unlike')->name('posts.unlike');
Route::resource('posts', PostLikeController::class);
Route::resource('posts', PostController::class);
Route::post('/posts/{post}/like', [PostLikeController::class, 'like'])->middleware('auth')->name('posts.like');
Route::delete('/posts/{post}/unlike', [PostLikeController::class, 'unlike'])->middleware('auth')->name('posts.unlike');

Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');

Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit')->middleware('auth');
Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update')->middleware('auth');


Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('auth');
Route::get('/register', [LoginController::class, 'register'])->name('register')->middleware('auth', 'passwords.confirm');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Auth::routes();
