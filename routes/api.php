<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\CupcakeController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(){
    return ['message' => 'Hello, world!'];
});

// Cupcake
Route::get('/cupcake', [CupcakeController::class, 'getCupcakes']);
Route::get('/cupcake/{id}',  [CupcakeController::class, 'getCupcake']);
Route::put('/cupcake/{id}',  [CupcakeController::class, 'updateCupcake']);
Route::post('/cupcake', [CupcakeController::class, 'createCupcake']);
Route::delete('/cupcake/{id}', [CupcakeController::class, 'deleteCupcake']);

// Cart
Route::post('/cart', [CartController::class, 'upsert']);
Route::get('/cart', [CartController::class, 'show']);

// Command
Route::get('/commands', [CommandController::class, 'index']);
Route::post('/command', [CommandController::class, 'store']);
Route::get('/command/{id}', [CommandController::class, 'show']);
Route::post('cancel_command', [CommandController::class, 'cancel']);
Route::post('confirm_command', [CommandController::class, 'confirm']);
