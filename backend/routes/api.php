<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductionController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('inventory', InventoryController::class);
    Route::get('inventory/{inventory}/forecast', [InventoryController::class, 'forecast']);

    Route::apiResource('orders', OrderController::class);
    Route::post('orders/{order}/items', [OrderController::class, 'addItem']);
    Route::get('orders/{order}/track', [OrderController::class, 'track']);

    Route::apiResource('productions', ProductionController::class);
    Route::get('productions/schedule/forecast', [ProductionController::class, 'forecastSchedule']);

    Route::get('export/inventory/excel', [InventoryController::class, 'exportExcel']);
    Route::get('export/inventory/pdf', [InventoryController::class, 'exportPdf']);
    Route::get('export/orders/excel', [OrderController::class, 'exportExcel']);
    Route::get('export/orders/pdf', [OrderController::class, 'exportPdf']);
    Route::get('export/productions/excel', [ProductionController::class, 'exportExcel']);
    Route::get('export/productions/pdf', [ProductionController::class, 'exportPdf']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// Public endpoints for customer order placement and tracking
Route::post('public/orders', [OrderController::class, 'storePublic']);
Route::get('public/orders/{order}/track', [OrderController::class, 'track']);