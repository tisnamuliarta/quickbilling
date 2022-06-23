<?php

use App\Http\Controllers\Transactions\BillController;
use App\Http\Controllers\Transactions\InvoiceController;
use Illuminate\Support\Facades\Route;

// List Sales routes
Route::apiResources([
    'invoice' => InvoiceController::class,
    'bill' => BillController::class,
]);
