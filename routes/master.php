<?php

use App\Http\Controllers\Master\MasterPermissionController;
use App\Http\Controllers\Master\MasterRolesController;
use App\Http\Controllers\Master\MasterUserController;
use App\Http\Controllers\Master\MasterUserDataController;
use App\Http\Controllers\Students\Api\BloodGroupController;
use App\Http\Controllers\Students\Api\DistrictController;
use App\Http\Controllers\Students\Api\ExpertiseController;
use App\Http\Controllers\Students\Api\ExtraCurricularController;
use App\Http\Controllers\Students\Api\HomeDataController;
use App\Http\Controllers\Students\Api\IncomeController;
use App\Http\Controllers\Students\Api\MajorController;
use App\Http\Controllers\Students\Api\ParentJobController;
use App\Http\Controllers\Students\Api\ProvinceController;
use App\Http\Controllers\Students\Api\RegencyController;
use App\Http\Controllers\Students\Api\ReligionController;
use App\Http\Controllers\Students\Api\ResidentController;
use App\Http\Controllers\Students\Api\SchoolStatusController;
use App\Http\Controllers\Students\Api\SpecialNeedsController;
use App\Http\Controllers\Students\Api\TimeLineController;
use App\Http\Controllers\Students\Api\TransportationController;
use App\Http\Controllers\Students\Api\VillageController;
use App\Http\Controllers\Students\Api\WorthyReasonController;
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
    'major' => MajorController::class,
    'expertise' => ExpertiseController::class,
    'extracurricular' => ExtraCurricularController::class,
    'blood-group' => BloodGroupController::class,
    'transportation' => TransportationController::class,
    'resident' => ResidentController::class,
    'religion' => ReligionController::class,
    'special-needs' => SpecialNeedsController::class,
    'province' => ProvinceController::class,
    'regency' => RegencyController::class,
    'district' => DistrictController::class,
    'village' => VillageController::class,
    'parent-job' => ParentJobController::class,
    'school-status' => SchoolStatusController::class,
    'pip-reason' => WorthyReasonController::class,
    'income' => IncomeController::class,
    'home-data' => HomeDataController::class,
    'time-line' => TimeLineController::class,
]);
