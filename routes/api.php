<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WooWebhookController;

Route::post('/woocommerce/orders', [WooWebhookController::class, 'order']);
Route::post('/woocommerce/products', [WooWebhookController::class, 'product']);
Route::post('/woocommerce/stockupdate', [WooWebhookController::class, 'stockupdate']);
Route::post('/woocommerce/categories', [WooWebhookController::class, 'category']);
Route::post('/woocommerce/inventory', [WooWebhookController::class, 'inventory']);
