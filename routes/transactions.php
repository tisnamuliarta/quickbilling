<?php

use App\Http\Controllers\Transactions\BillController;
use App\Http\Controllers\Transactions\InvoiceController;
use App\Http\Controllers\Transactions\TransactionController;
use Illuminate\Support\Facades\Route;

// List Sales routes
Route::apiResources([
    'transaction' => TransactionCOntroller::class,
    'invoice' => InvoiceController::class,
    'bill' => BillController::class,
]);
