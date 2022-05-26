<?php

use App\Http\Controllers\Master\BankController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\MasterPermissionController;
use App\Http\Controllers\Master\MasterRolesController;
use App\Http\Controllers\Master\MasterUserController;
use App\Http\Controllers\Master\MasterUserDataController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {
    Route::get('permission', [MasterUserDataController::class, 'userPermission']);
    Route::post('roles', [MasterUserDataController::class, 'userRoles']);
    Route::post('permission', [MasterUserDataController::class, 'storeUserPermission']);
});

Route::get('permission-role', [MasterRolesController::class, 'permissionRole']);
Route::post('permission-role', [MasterRolesController::class, 'storePermissionRole']);

Route::apiResources([
    'permissions' => MasterPermissionController::class,
    'roles' => MasterRolesController::class,
    'users' => MasterUserController::class,

    'banks' => BankController::class,
    'categories' => CategoryController::class,
]);
