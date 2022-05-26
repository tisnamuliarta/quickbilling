<?php

use Illuminate\Support\Facades\Route;

Route::apiResources([
    'accounts' => \App\Http\Controllers\Financial\AccountController::class,
    'reconcile' => \App\Http\Controllers\Financial\ReconcileController::class,
    'account-mapping' => \App\Http\Controllers\Financial\AccountMappingController::class,
    'account-category' => \App\Http\Controllers\Financial\AccountCategoryController::class,
    'reporting-period' => \App\Http\Controllers\Financial\ReportingPeriodController::class,
    'currency' => \App\Http\Controllers\Financial\CurrencyController::class,
    'taxes' => \App\Http\Controllers\Financial\TaxController::class,
    'payment-terms' => \App\Http\Controllers\Financial\PaymentTermController::class,
    'payment-methods' => \App\Http\Controllers\Financial\PaymentMethodController::class,
]);
