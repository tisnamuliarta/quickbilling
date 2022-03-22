<?php

use App\Http\Controllers\Financial\AccountController;
use App\Http\Controllers\Financial\CurrencyController;
use App\Http\Controllers\Financial\PaymentMethodController;
use App\Http\Controllers\Financial\PaymentTermController;
use App\Http\Controllers\Financial\TaxController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'accounts' => AccountController::class,
    'currency' => CurrencyController::class,
    'taxes' => TaxController::class,
    'payment-terms' => PaymentTermController::class,
    'payment-methods' => PaymentMethodController::class,
]);
