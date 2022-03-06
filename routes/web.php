<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Products\ProductBrandController;
use App\Http\Controllers\Products\ProductCategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\ProductFeatureController;
use App\Http\Controllers\Products\ProductPriceListController;
use App\Http\Controllers\Products\ProductReviewController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesOrderStatusController;
use App\Http\Controllers\Sales\SalesPersonController;
use App\Http\Controllers\Sales\SpecialOfferController;
use App\Http\Controllers\Students\Frontend\StudentRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::get('logo', [\App\Http\Controllers\Settings\LogoController::class, 'index']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::group(['prefix' => 'registration'], function () {
        Route::get('/print', [StudentRegisterController::class, 'export'])->name('ppdb.print.registration');
        Route::post('/register', [StudentRegisterController::class, 'store'])->name('ppdb.register.post');
        Route::post('/forget-id', [StudentRegisterController::class, 'storeForgetId'])->name('ppdb.forgetId.post');
        Route::post('/announcement', [StudentRegisterController::class, 'studentAnnouncement']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/roles', [AuthController::class, 'roles']);
            Route::post('/permissions', [AuthController::class, 'permissions']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });

        Route::get('menus', [AuthController::class, 'menus']);

        Route::group(['prefix' => 'products'], function () {
            Route::apiResources([
                'review' => ProductReviewController::class,
                'category' => ProductCategoryController::class,
                'price-list' => ProductPriceListController::class,
                'feature' => ProductFeatureController::class,
                'brand' => ProductBrandController::class,
                'product' => ProductController::class,
            ]);
        });

        Route::group(['prefix' => 'sales'], function () {
            Route::apiResources([
                'order' => SalesOrderController::class,
                'person' => SalesPersonController::class,
                'status' => SalesOrderStatusController::class,
                'special-offer' => SpecialOfferController::class,
            ]);
        });

        Route::prefix('student')
            ->group(__DIR__ . '/student.php');

        Route::prefix('master')
            ->group(__DIR__ . '/master.php');
    });
});

// Add this route last as a catch all for undefined routes.
Route::get(
    '/{path?}',
    function (Request $request) {
        // ...
        // If the request expects JSON, it means that
        // someone sent a request to an invalid route.
        if ($request->expectsJson()) {
            abort(404);
        }

        // Fetch and display the page from the render path on nuxt dev server or fallback to static file
        return file_get_contents(env('NUXT_OUTPUT_PATH', public_path('spa.html')));
    }
)->where('path', '.*')
    // Redirect to Nuxt from within Laravel
    // by using Laravels route helper
    // e.g.: `route('nuxt', ['path' => '/<nuxtPath>'])`
    ->name('nuxt');
