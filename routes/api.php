<?php

use App\Http\Controllers\Api\V1\CustomerApiController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\StockApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('api.client:read_products')->get('/products', [ProductApiController::class, 'index']);
    Route::middleware('api.client:read_products')->get('/products/{product}', [ProductApiController::class, 'show']);
    Route::middleware('api.client:read_stock')->get('/stock', [StockApiController::class, 'index']);
    Route::middleware('api.client:read_stock')->get('/stock/{product}', [StockApiController::class, 'show']);
    Route::middleware('api.client:read_orders')->get('/orders', [OrderApiController::class, 'index']);
    Route::middleware('api.client:read_orders')->get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::middleware('api.client:write_orders')->post('/orders', [OrderApiController::class, 'store']);
    Route::middleware('api.client:read_customers')->get('/customers', [CustomerApiController::class, 'index']);
    Route::middleware('api.client:write_customers')->post('/customers', [CustomerApiController::class, 'store']);
});
