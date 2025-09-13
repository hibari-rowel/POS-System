<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/fetch_user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
});
