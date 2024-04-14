<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\UserController;


//login functionalities
Route::get('/login', [LoginController::class, 'login'])->name('login')->middleware('auth');
Route::get('/register', [LoginController::class, 'register'])->name('register')->middleware('auth', 'passwords.confirm');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth.user');

Route::middleware('auth.user')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::resource('comments', CommentController::class)->only(['index', 'create', 'show']);
    Route::resource('posts', PostController::class)->only(['index', 'create', 'show']);
    Route::get('/users/liked', [PostLikeController::class, 'show_liked_posts'])->name('users.liked');

});

Route::middleware('auth.userblocked')->group(function () {
    Route::resource('comments', CommentController::class)->only(['store', 'edit', 'update', 'destroy']);
    Route::resource('posts', PostController::class)->only(['store', 'edit', 'update', 'destroy']);
    Route::post('/posts/{post}/like', [PostLikeController::class, 'like'])->middleware('auth')->name('posts.like');
    Route::delete('/posts/{post}/unlike', [PostLikeController::class, 'unlike'])->middleware('auth')->name('posts.unlike');
    
    //require only user to post it
    Route::get('/posts/{post}/report', [ReportController::class, 'create_post_report'])->name('posts.report');
    Route::post('/posts/{post}/report', [ReportController::class, 'report_post'])->name('posts.report_post');

    Route::get('/comments/{comment}/report', [ReportController::class, 'create_comment_report'])->name('comments.report');
    Route::post('/comments/{comment}/report', [ReportController::class, 'report_comment'])->name('comments.report_comment');

});



Route::resource('users', FollowerController::class);
Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('auth.user');
Route::Post('/users/{user}/follow', [FollowerController::class, 'follow'])->name('users.follow')->middleware('auth.user');
Route::delete('/users/{user}/unfollow', [FollowerController::class, 'unfollow'])->name('users.unfollow')->middleware('auth.user');


Route::middleware('auth.admin')->group(function () {
    Route::prefix('warnings')->group(function () {
        Route::get('/flaggedPosts', [AdminController::class, 'posts_flagged'])->name('posts_flagged');
        Route::get('/flaggedComments', [AdminController::class, 'comments_flagged'])->name('comments_flagged');
        Route::get('/reportedPosts', [ReportController::class, 'posts_reported'])->name('posts_reported');
        Route::get('/reportedComments', [ReportController::class, 'comments_reported'])->name('comments_reported');
    });

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::put('/admin/{user}/block_user', [AdminController::class, 'block_user'])->name('admin.block_user');
    Route::put('/admin/{user}/set_user_support', [AdminController::class, 'set_user_support'])->name('admin.set_user_support');

    Route::prefix('admin/{user}')->group(function () {
        Route::get('/user_reported_comments', [ReportController::class, 'show_user_reported_comments'])->name('admin.reported_comments');
        Route::get('/user_reported_posts', [ReportController::class, 'show_user_reported_posts'])->name('admin.reported_posts');
        Route::get('/user_flaged_comments', [AdminController::class, 'show_user_flagged_comments'])->name('admin.flagged_comments');
        Route::get('/user_flags_posts', [AdminController::class, 'show_user_flagged_posts'])->name('admin.flagged_posts');

        Route::prefix('reports')->group(function () {
            Route::get('/{post}/post_reports', [ReportController::class, 'show_post_reports'])->name('reports.post_report');
            Route::get('/{comment}/comment_reports', [ReportController::class, 'show_comment_reports'])->name('reports.comment_report');
        });
    });

    Route::delete('/post_reports/{report}/delete_post_report', [ReportController::class, 'delete_post_report'])->name('reports.delete_post_report');
    Route::delete('/comment_reports/{report}/delete_comment_report', [ReportController::class, 'delete_comment_report'])->name('reports.delete_comment_report');
    Route::delete('/reports/{post}/post_destroy', [ReportController::class, 'post_destroy'])->name('posts.post_destroy');
    Route::delete('/reports/{comment}/comment_destroy', [ReportController::class, 'comment_destroy'])->name('comments.comment_destroy');

    Route::resource('users', FollowerController::class);
});

Auth::routes();
