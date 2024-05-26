<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PublishersController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Models\Blog;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');





Route::middleware('auth')->group(function () {

    Route::get('/publishers', [PublishersController::class, 'index']);

});


Route::middleware(['auth', 'can:edit-user,user'])->group(function () {

    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::get('/users/{user}/stats', [UserController::class, 'stats']);

    Route::get('/users/{user}/edit', [UserController::class, 'edit']);

    Route::patch('users/{user}', [UserController::class, 'update']);

    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});



//Auth

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
