<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminDepositController;
use App\Http\Controllers\DepositController;

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
Route::prefix('tickets')->middleware(['auth:sanctum'])->controller(TicketController::class)->group(function () {
  Route::get('', 'index');
  Route::post('', 'store');
  Route::get('{uuid}', 'show');
  Route::put('{uuid}/close', 'close');
  Route::post('{uuid}/messages', 'storeMessage');
});
Route::prefix('deposits')->middleware(['auth:sanctum'])->controller(DepositController::class)->group(function () {
  Route::get('/', 'index');
  Route::post('/', 'store');
});
Route::prefix('projects')->controller(ProjectController::class)->group(function () {
  Route::get('/{uuid}/comments', 'getComments');
  Route::get('/', 'index');
  Route::get('/{uuid}', 'show');
  Route::middleware(['auth:sanctum'])->controller(ProjectController::class)->group(function () {
  
    Route::post('/{uuid}/comments', 'storeComment');
    
  });

});


Route::middleware(['auth:sanctum'])->prefix('user')->controller(UserController::class)->group(function () {
  Route::get('/profile', 'getProfile');
});

//admin
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {


  Route::prefix('deposits')->controller(AdminDepositController::class)->group(function () {
    Route::get('', 'getDeposits');
    Route::post('{deposit_uuid}', 'changeDepositStatus');
    Route::put('{deposit_uuid}/status', 'changeDepositStatus');
    Route::patch('{deposit_uuid}', 'update');
});


  Route::prefix('companies')->controller(CompaniesController::class)->group(function () {
    Route::get('/', 'index');
  });
  Route::prefix('comments')->controller(CommentController::class)->group(function () {
    Route::get('/', 'index');
  });
  
  Route::prefix('tickets')->controller(AdminTicketController::class)->group(function () {
    Route::get('', 'getTickets');
    Route::get('{uuid}', 'showTicket');
    Route::put('{uuid}/status', 'changeTicketStatus');
    Route::post('{uuid}/message', 'storeMessage');
});

  Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/profile', 'getProfile');
  });
  Route::prefix('project')->controller(ProjectController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/', 'index');
    Route::post('/{uuid}', 'update');
    Route::post('/{uuid}/media', 'uploadMedia');
    Route::delete('/{uuid}', 'forceDelete');
    Route::delete('/{uuid}/media/{media_uuid}', 'deleteMedia');
  });
  Route::prefix('projects/{uuid}')->controller(CommentController::class)->group(function () {
    Route::get('comments', 'getProjectComments');
    Route::put('comments/{comment_uuid}/verify', 'verifyProjectComment');
});
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
