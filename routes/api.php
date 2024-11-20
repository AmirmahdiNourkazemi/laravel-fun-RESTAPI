<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;


Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('signin','signin');
});

Route::middleware(['auth:sanctum'])->prefix('companies')->controller(CompaniesController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{uuid}', 'show');
    Route::post('/', 'store');
    Route::patch('/{uuid}', 'update');
    Route::delete('/{uuid}', 'delete');
});


Route::middleware(['auth:sanctum'])->prefix('projects')->controller(ProjectController::class)->group(function () {
  Route::get('/', 'index');
});


//admin
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
  Route::prefix('companies')->controller(CompaniesController::class)->group(function () {
    Route::get('/', 'index');
  });
  Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
  });
  Route::prefix('project')->controller(ProjectController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/', 'index');
    Route::post('/{uuid}', 'update');
    Route::post('/{uuid}/media', 'uploadMedia');
  });
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
