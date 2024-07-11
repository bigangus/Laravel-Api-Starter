<?php

use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['throttle:60,1'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('login', [UserController::class, 'login']);
        Route::post('register', [UserController::class, 'register']);

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [UserController::class, 'logout']);
            Route::post('info', [UserController::class, 'info'])->middleware('acl:user.info');
            Route::post('permissions', [UserController::class, 'permissions']);
            Route::post('roles', [UserController::class, 'roles']);
            Route::post('update-password', [UserController::class, 'updatePassword']);
        });
    });
});
