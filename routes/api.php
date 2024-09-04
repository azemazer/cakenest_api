<?php

use App\Http\Controllers\CupcakeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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