<?php

use App\Http\Controllers\Students\Api\StudentHelpDataController;
use App\Http\Controllers\Students\Api\PlaceController;
use App\Http\Controllers\Students\Api\ProspectiveStudentController;
use App\Http\Controllers\Students\Api\ReRegistrationController;
use App\Http\Controllers\Students\Api\StudentController;
use App\Http\Controllers\Students\Api\StudentDetailsController;
use App\Http\Controllers\Students\Api\StudentFileController;
use App\Http\Controllers\Students\Api\StudentParentController;
use App\Http\Controllers\Students\Api\StudentRegistrationController;
use App\Http\Controllers\Students\Api\StudentScoreController;
use Illuminate\Support\Facades\Route;

Route::get('prospective-students-export', [ProspectiveStudentController::class, 'exportData']);
Route::get('students', [StudentController::class, 'index']);

Route::get('re-registration', [ReRegistrationController::class, 'index']);
Route::get('re-registration/{id}', [ReRegistrationController::class, 'dataByDetails']);
Route::get('student-photo', [StudentDetailsController::class, 'studentPhoto']);
Route::get('student-photo/{id}', [StudentDetailsController::class, 'studentPhotoByUser']);
Route::get('download-photo/{id}', [StudentDetailsController::class, 'downloadUserImage']);
Route::get('student-details', [StudentDetailsController::class, 'details']);
Route::get('student-details/{id}', [StudentDetailsController::class, 'detailsByUser']);
Route::get('student-data-help', [StudentHelpDataController::class, 'index']);
Route::get('student-data-help/{id}', [StudentHelpDataController::class, 'indexByUser']);
Route::get('student-data-parent', [StudentParentController::class, 'index']);
Route::get('student-data-parent/{id}', [StudentParentController::class, 'indexByUser']);
Route::get('student-score', [StudentScoreController::class, 'score']);
Route::get('student-score/{id}', [StudentScoreController::class, 'scoreByUser']);
Route::get('student-files', [StudentFileController::class, 'index']);

Route::get('regency', [PlaceController::class, 'regency']);
Route::get('districts', [PlaceController::class, 'districts']);
Route::get('villages', [PlaceController::class, 'villages']);

Route::post('student-photo', [StudentDetailsController::class, 'storePhoto']);
Route::post('student-details', [StudentDetailsController::class, 'store']);
Route::post('student-data-help', [StudentHelpDataController::class, 'store']);
Route::post('student-data-parent', [StudentParentController::class, 'store']);
Route::post('student-score', [ReRegistrationController::class, 'index']);
Route::post('student-files', [StudentFileController::class, 'store']);

Route::post('student-send-data', [ReRegistrationController::class, 'sendData']);

Route::apiResource('student-registration', StudentRegistrationController::class);
Route::apiResource("prospective-students", ProspectiveStudentController::class);
