<?php

use App\Http\Controllers\Financial\AccountController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'accounts' => AccountController::class
]);
