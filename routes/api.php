<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SpkController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::patch('products/{product}/price', [ProductController::class, 'updatePrice']);
    
    Route::post('spk/calculate', [SpkController::class, 'calculate']);
    Route::get('spk/criteria', [SpkController::class, 'criteria']);
});