<?php

use Illuminate\Support\Facades\Route;

Route::apiResources([
    'payrolls' => \App\Http\Controllers\Payroll\PayrollController::class,
    'employees' => \App\Http\Controllers\Payroll\EmployeeController::class,
    'contractors' => \App\Http\Controllers\Payroll\ContractorController::class,
    'work-locations' => \App\Http\Controllers\Payroll\WorkLocationController::class,
]);
