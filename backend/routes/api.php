<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductCategoriesController;

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

    Route::prefix('product_categories')->group(function () {
        Route::post('/get_list', [ProductCategoriesController::class, 'getList']);
        Route::get('/get/{product_category}', [ProductCategoriesController::class, 'get']);
        Route::post('/create', [ProductCategoriesController::class, 'store']);
        Route::put('/update/{product_category}', [ProductCategoriesController::class, 'update']);
        Route::delete('/destroy/{product_category}', [ProductCategoriesController::class, 'destroy']);
        Route::get('/get_dropdown_list', [ProductCategoriesController::class, 'getDropdownList']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/get_list', [ProductController::class, 'getList']);
        Route::get('/get/{product}', [ProductController::class, 'get']);
        Route::post('/create', [ProductController::class, 'store']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::delete('/destroy/{product}', [ProductController::class, 'destroy']);
        Route::get('/get_dropdown_list', [ProductController::class, 'getDropdownList']);
    });
});
