<?php

use App\Http\Controllers\Purchase\PurchaseInvoiceController;
use App\Http\Controllers\Purchase\PurchaseOrderController;
use App\Http\Controllers\Purchase\PurchasePaymentController;
use App\Http\Controllers\Purchase\PurchaseQuotationController;
use App\Http\Controllers\Purchase\PurchaseReceiptController;
use App\Http\Controllers\Purchase\PurchaseReturnController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'quotation' => PurchaseQuotationController::class,
    'order' => PurchaseOrderController::class,
    'receipt' => PurchaseReceiptController::class,
    'invoice' => PurchaseInvoiceController::class,
    'return' => PurchaseReturnController::class,
    'payment' => PurchasePaymentController::class,
]);
