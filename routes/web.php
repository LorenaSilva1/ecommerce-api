<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'API Laravel funcionando! 🚀',
        'status' => 'ok',
    ]);
});

Route::get('/produtos', function () {
    return view('products');
});
