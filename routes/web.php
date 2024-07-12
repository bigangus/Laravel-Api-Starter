<?php

use Illuminate\Support\Facades\Route;

$controllersMethods = [];
$directory = app_path('Http/Controllers');
$excludeMethods = ['__construct', 'middleware'];

scan_directory($directory, $controllersMethods, $excludeMethods);

/**
 * Do not modify this file, routes will be generated automatically
 */
Route::prefix('api')->middleware(['throttle:60,1'])->group(function () use ($controllersMethods) {
    foreach ($controllersMethods as $prefix => $controllerInfo) {
        Route::prefix($prefix)->group(function () use ($controllerInfo, $prefix) {
            foreach ($controllerInfo['methods'] as $method) {
                Route::post(
                    Str::replace('_', '-', Str::snake($method)),
                    "{$controllerInfo['className']}@$method"
                )->name(Str::replace('/', '.', $prefix) . '.' . $method);
            }
        });
    }
});
