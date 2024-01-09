<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*Route::group([
    'middleware' => ['auth:sanctum'],
    'prefix' => '',
    'as' => 'users.',
], function () {
    Route::get('/users', [UserController::class, 'index'])->name('index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('show');
    Route::post('/users', [UserController::class, 'store'])->name('store');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
});*/

Route::middleware([
    //'auth:sanctum',
])
    ->name('users.')
    ->namespace("\App\Http\Controllers")
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->withoutMiddleware('auth')
            ->name('index');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('show')
            ->whereNumber('user');
        Route::post('/users', [UserController::class, 'store'])->name('store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
