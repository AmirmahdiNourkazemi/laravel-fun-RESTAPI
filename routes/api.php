<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('signin','signin');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
