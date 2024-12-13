<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\CupcakeController;
use App\Http\Controllers\PromocodeController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(){
    return ['message' => 'Hello, world!'];
});

// Cupcake
Route::get('/cupcake', [CupcakeController::class, 'getCupcakes']);
Route::get('/cupcake/{id}',  [CupcakeController::class, 'getCupcake']);
Route::put('/cupcake/{id}',  [CupcakeController::class, 'updateCupcake'])->middleware(EnsureUserIsAdmin::class);
Route::post('/cupcake', [CupcakeController::class, 'createCupcake'])->middleware(EnsureUserIsAdmin::class);
Route::delete('/cupcake/{id}', [CupcakeController::class, 'deleteCupcake'])->middleware(EnsureUserIsAdmin::class);

// Cart
Route::middleware(['auth:sanctum'])->post('/cart', [CartController::class, 'upsert']);
Route::middleware(['auth:sanctum'])->post('/cart/remove/{id}', [CartController::class, 'remove_item']);
Route::middleware(['auth:sanctum'])->post('/cart/empty', [CartController::class, 'empty']);
Route::middleware(['auth:sanctum'])->get('/cart', [CartController::class, 'show']);

// Command
Route::get('/commands', [CommandController::class, 'index']);
Route::post('/command', [CommandController::class, 'store']);
Route::get('/command/{id}', [CommandController::class, 'show']);
Route::post('cancel_command', [CommandController::class, 'cancel']);
Route::post('confirm_command', [CommandController::class, 'confirm']);

// Promocode
Route::get('/promocode/submit', [PromocodeController::class, 'getPromocodeByCode']);