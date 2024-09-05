<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\LogController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api','add.headers'],'prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('auth.refresh');
        Route::get('/verify', [AuthController::class, 'verify'])->middleware('auth:api');
    });

    Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/me', [UserController::class, 'me'])->name('users.me');
    });

    Route::group(['prefix' => 'logs', 'middleware' => 'auth:api'], function () {
        Route::get('/', [LogController::class, 'index']);
        Route::get('/{id}', [LogController::class, 'show'])->name('logs.show');
        Route::put('/{id}', [LogController::class, 'update'])->name('logs.update');
        Route::delete('/{id}', [LogController::class, 'destroy'])->name('logs.destroy');
        Route::get('/me', [LogController::class, 'me'])->name('logs.me');
    });

});
