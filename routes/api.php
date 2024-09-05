<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
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