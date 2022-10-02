<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessPartner\ContactController;
use App\Http\Controllers\BusinessPartner\ContactTransactionController;
use App\Http\Controllers\Common\ChartController;
use App\Http\Controllers\Common\CopyDocumentController;
use App\Http\Controllers\Common\SearchController;
use App\Http\Controllers\Common\TagController;
use App\Http\Controllers\Documents\DocumentController;
use App\Http\Controllers\Documents\DocumentExportController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Inventory\IssueController;
use App\Http\Controllers\Inventory\ItemCategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ItemGroupController;
use App\Http\Controllers\Inventory\ItemUnitController;
use App\Http\Controllers\Inventory\PriceListController;
use App\Http\Controllers\Inventory\ReceiptController;
use App\Http\Controllers\Inventory\ResourceController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Payroll\DeductionController;
use App\Http\Controllers\Payroll\LoanController;
use App\Http\Controllers\Payroll\EmployeeCommissionController;
use App\Http\Controllers\Payroll\EmployeeController;
use App\Http\Controllers\Payroll\LoanTypeController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Payroll\PayScheduleController;
use App\Http\Controllers\Payroll\PayTypeController;
use App\Http\Controllers\Payroll\TimesheetController;
use App\Http\Controllers\Payroll\WorkLocationController;
use App\Http\Controllers\Production\ProductionController;
use App\Http\Controllers\Production\ProductionIssueController;
use App\Http\Controllers\Production\ProductionReceiptController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Settings\EntityController;
use App\Http\Controllers\Settings\LogoController;
use App\Http\Controllers\Settings\SettingController;
use App\Http\Controllers\Transactions\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('logo', [LogoController::class, 'index']);
// List nav bar menu for guest
Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('date-filter', [AuthController::class, 'dateFilter']);
    Route::get('menus', [AuthController::class, 'menus']);
    Route::get('chart', [ChartController::class, 'index']);

    Route::get('tags', [TagController::class, 'index']);
    Route::get('search', [SearchController::class, 'search']);

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/roles', [AuthController::class, 'roles']);
        Route::post('/permissions', [AuthController::class, 'permissions']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
    // Inventory routes
    Route::group(['prefix' => 'inventory'], function () {
        Route::apiResources([
            'items' => ItemController::class,
            'item-units' => ItemUnitController::class,
            'item-category' => ItemCategoryController::class,
            'item-groups' => ItemGroupController::class,
            'price-list' => PriceListController::class,
            'warehouse' => WarehouseController::class,
            'goods-receipt' => ReceiptController::class,
            'goods-issue' => IssueController::class,
            'resource' => ResourceController::class,
        ]);
    });

    Route::group(['prefix' => 'bp'], function () {
        Route::delete('delete-bank/{id}', [ContactController::class, 'deleteBank']);
        Route::delete('delete-email/{id}', [ContactController::class, 'deleteEmail']);
        Route::get('transaction/{id}', [ContactTransactionController::class, 'index']);
        Route::apiResources([
            'contacts' => ContactController::class,
        ]);
    });

    // List all master routes
    Route::prefix('master')
        ->group(__DIR__ . '/master.php');

    // payroll route
    Route::group(['prefix' => 'payroll'], function () {
        Route::get('pay-period', [PayrollController::class, 'payPeriod']);
        Route::get('list-commission', [EmployeeCommissionController::class, 'index']);
        Route::get('employee-commission', [PayrollController::class, 'getEmployeeCommission']);
        Route::get('print/{id}', [PayrollController::class, 'printSlip']);
        Route::get('summary/{id}', [PayrollController::class, 'summary']);
        Route::get('loan-installment/{id}', [LoanController::class, 'loanInstallment']);

        Route::apiResources([
            'employees' => EmployeeController::class,
            'pay-types' => PayTypeController::class,
            'pay-schedules' => PayScheduleController::class,
            'payroll' => PayrollController::class,
            'loan' => LoanController::class,
            'loan-type' => LoanTypeController::class,
            'deduction' => DeductionController::class,
            'timesheet' => TimesheetController::class,
            // 'contractors' => \App\Http\Controllers\Payroll\ContractorController::class,
            'work-locations' => WorkLocationController::class,
        ]);
    });

    // List all sales routes
    Route::group(['prefix' => 'transaction'], function () {
        Route::get('ledger/{id}', [TransactionController::class, 'getLedger']);
        Route::get('invoice', [TransactionController::class, 'getInvoice']);
        Route::get('group', [TransactionController::class, 'groupTransaction']);
    });

    Route::apiResources([
        'transactions' => TransactionController::class,
    ]);

    Route::get('report-list', [ReportController::class, 'index']);
    Route::get('report', [ReportController::class, 'preview']);
    Route::get('report/pdf', [DocumentExportController::class, 'reportPdf']);
    Route::get('report/excel', [DocumentExportController::class, 'reportExcel']);

    // List all documents routes
    Route::post('document-files', [FileController::class, 'store']);
    Route::get('document-files', [FileController::class, 'index']);
    Route::delete('document-files', [FileController::class, 'destroy']);

    Route::group(['prefix' => 'document'], function () {
        Route::get('audit/{id}', [DocumentController::class, 'getAudit']);
        Route::get('copy', [CopyDocumentController::class, 'copyDocument']);
        Route::get('print', [DocumentExportController::class, 'print']);
        Route::post('email', [DocumentExportController::class, 'email']);
    });

    Route::group(['prefix' => 'production'], function () {
        Route::get('order/arrow', [ProductionController::class, 'arrowAction']);
        Route::apiResource('order', ProductionController::class);
        Route::apiResource('issue', ProductionIssueController::class);
        Route::apiResource('receipt', ProductionReceiptController::class);
    });

    Route::get('documents/arrow', [DocumentController::class, 'arrowAction']);
    Route::apiResource('documents', DocumentController::class);

    // List all master routes
    Route::prefix('financial')
        ->group(__DIR__ . '/financial.php');

    // Route Resource for settings
    Route::apiResources([
        'entities' => EntityController::class,
        'settings' => SettingController::class,
    ]);
});
