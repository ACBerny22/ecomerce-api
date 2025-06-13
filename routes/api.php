<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);

Route::get('/unauth', function (Request $request) {
    return response()->json([
        'message' => 'Not authenticated'
    ]);
})->name('unauth');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', function (Request $request) {
    return response()->json([
        'status' => 'ok',
    ]);
})->middleware('auth:sanctum');

Route::post('/categories', [CategoryController::class, "createCategory"])->middleware('auth:sanctum');
Route::get('/categories', [CategoryController::class, "fetchCategories"])->middleware('auth:sanctum');
Route::get('/products', [ProductController::class, "fetchProducts"])->middleware('auth:sanctum');
Route::post('/products', [ProductController::class, "createProduct"])->middleware('auth:sanctum');
