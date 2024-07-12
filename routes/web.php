<?php

use Illuminate\Support\Facades\Route;

$directory = app_path('Http/Controllers');
$subfolders = array_filter(scandir($directory), function ($entry) use ($directory) {
    return $entry !== '.' && $entry !== '..' && is_dir($directory . '/' . $entry);
});

$controllersMethods = [];

foreach ($subfolders as $subfolder) {
    $subfolderPath = $directory . '/' . $subfolder;
    $files = array_filter(scandir($subfolderPath), function ($file) use ($subfolderPath) {
        return is_file($subfolderPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php';
    });

    foreach ($files as $file) {
        $className = pathinfo($file, PATHINFO_FILENAME);
        $fullClassName = "App\\Http\\Controllers\\$subfolder\\$className";

        if (class_exists($fullClassName)) {
            $reflectionClass = new ReflectionClass($fullClassName);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            $methodNames = [];

            foreach ($methods as $method) {
                if ($method->class == $fullClassName) {
                    $methodNames[] = $method->name;
                }
            }

            $controllersMethods[$fullClassName] = $methodNames;
        }
    }
}

Route::prefix('api')->middleware(['throttle:60,1'])->group(function () use ($controllersMethods) {
    foreach ($controllersMethods as $fullClassName => $methods) {
        Route::prefix(Str::lower(explode('\\', $fullClassName)[3]))->group(function () use ($fullClassName, $methods) {
            foreach ($methods as $method) {
                Route::post(
                    Str::replace('_', '-', Str::snake($method)),
                    "$fullClassName@$method"
                );
            }
        });
    }
});


//Route::prefix('users')->group(function () {
//    Route::post('login', [UserController::class, 'login']);
//    Route::post('register', [UserController::class, 'register']);
//
//    Route::middleware(['auth:sanctum'])->group(function () {
//        Route::post('logout', [UserController::class, 'logout']);
//        Route::post('info', [UserController::class, 'info'])->middleware('acl:user.info');
//        Route::post('permissions', [UserController::class, 'permissions']);
//        Route::post('roles', [UserController::class, 'roles']);
//        Route::post('update-password', [UserController::class, 'updatePassword']);
//        Route::post('update-profile', [UserController::class, 'updateProfile']);
//    });
//});
