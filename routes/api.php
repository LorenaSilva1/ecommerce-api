<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return response()->json(['message' => 'API funcionando!']);
});

// Rotas de produtos
Route::apiResource('products', ProductController::class);

// Rotas de categorias
Route::apiResource('categories', CategoryController::class);
