<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/fetch_user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });

    Route::prefix('users')->group(function () {
        Route::post('/get_list', [UserController::class, 'getList']);
        Route::get('/get/{user}', [UserController::class, 'get']);
        Route::post('/create', [UserController::class, 'store']);
        Route::put('/update/{user}', [UserController::class, 'update']);
        Route::delete('/destroy/{user}', [UserController::class, 'destroy']);
    });
});
