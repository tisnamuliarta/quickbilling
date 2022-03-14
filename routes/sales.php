<?php

use App\Http\Controllers\Sales\SalesDeliveryController;
use App\Http\Controllers\Sales\SalesInvoiceController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesPaymentController;
use App\Http\Controllers\Sales\SalesQuotationController;
use App\Http\Controllers\Sales\SalesReturnController;
use Illuminate\Support\Facades\Route;

// List Sales routes
Route::apiResources([
    'quotation' => SalesQuotationController::class,
    'order' => SalesOrderController::class,
    'delivery' => SalesDeliveryController::class,
    'invoice' => SalesInvoiceController::class,
    'return' => SalesReturnController::class,
    'payment' => SalesPaymentController::class,
]);
