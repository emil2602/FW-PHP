<?php

use App\Controllers\PostController;
use Fw\PhpFw\Routing\Route;
use App\Controllers\HomeController;


return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id:\d+}', [PostController::class, 'show']),
    Route::get('/posts/create', [PostController::class, 'create'])
];