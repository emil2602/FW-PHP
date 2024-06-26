<?php

use App\Controllers\LoginController;
use App\Controllers\PostController;
use App\Controllers\RegisterController;
use Fw\PhpFw\Routing\Route;
use App\Controllers\HomeController;


return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::post('/posts', [PostController::class, 'store']),
    Route::get('/register', [RegisterController::class, 'form']),
    Route::post('/register', [RegisterController::class, 'register']),
    Route::get('/login', [LoginController::class, 'form']),
    Route::post('/login', [LoginController::class, 'login']),
];