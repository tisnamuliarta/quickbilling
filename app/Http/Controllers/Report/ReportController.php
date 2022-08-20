<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Settings\Entity;
use App\Services\Reports\ReportService;
use Carbon\Carbon;
use IFRS\Reports\AccountStatement;
use IFRS\Reports\BalanceSheet;
use IFRS\Reports\CashFlowStatement;
use IFRS\Reports\IncomeStatement;
use IFRS\Reports\TrialBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
    public ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \IFRS\Exceptions\MissingAccount
     * @throws \Exception
     */
    public function preview(Request $request)
    {
        App::setLocale(auth()->user()->locale);
        Carbon::setLocale(auth()->user()->locale);
        $report_type = strtoupper($request->report_type);
        $account_id = $request->account_id;
        $first_date = date('Y-m-') . '01';
        $end_day = date('Y-m-t');
        $start_date = (isset($request->start_date)) ? $request->start_date : $first_date;
        $end_date = (isset($request->end_date)) ? $request->end_date : $end_day;

        $entity = Entity::find($request->user()->entity_id);

        switch ($report_type) {
            case strtoupper('Account Balance'):
                $report = new AccountStatement($account_id, null, $start_date, $end_date);
                return $this->success([
                    'data' => $report->getTransactions(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Profit and loss statement'):
                $report = new IncomeStatement($start_date, $end_date, $entity);
                //$data = $this->service->transformProfitAndLoss($report->getSections());
                return $this->success([
                    'data' => collect($report->getSections($start_date, $end_date)),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);
            //return $this->success(['data' => $report->getResults(date('m'), date('Y'), $entity)]);

            case strtoupper('Balance sheet'):
                $report = new BalanceSheet($end_date, $entity);
                return $this->success([
                    'data' => $report->getSections(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Trial Balance'):
                $report = new TrialBalance($end_date, $entity);
                return $this->success([
                    'data' => $report->getSections(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Statement of cash flow'):
            case strtoupper('Statement of cash flows'):
                $report = new CashFlowStatement($start_date, $end_date, $entity);
                return $this->success([
                    'data' => $report->getSections(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Product/Service List'):
                $report = $this->service->getItemList();
                return $this->success(['data' => $report]);
        }

        throw new \Exception('No report match');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        App::setLocale(auth()->user()->locale);

        return $this->success([
            'data' => [
                [
                    "id" => 1,
                    "name" => __('Business Overview'),
                    "alias" => 'Business Overview',
                    "children" => [
                        [
                            "id" => 11,
                            "name" => __('Audit Log'),
                            "alias" => 'Audit Log',
                            "route" => '/app/reports/report/audit-log'],
                        [
                            "id" => 12,
                            "name" => __('Statement of cash flow'),
                            "alias" => 'Statement of cash flow',
                            "route" => '/app/reports/report/cash-flow'
                        ],
                        [
                            "id" => 13,
                            "name" => __('Balance sheet'),
                            "alias" => 'Balance sheet',
                            "route" => '/app/reports/report/balance-sheet'],
                        // [ "id" => 14, "name" => 'Balance Sheet Comparison', "route" => '' ],
                        // [ "id" => 15, "name" => 'Balance Sheet Detail', "route" => '' ],
                        // [ "id" => 16, "name" => 'Balance Sheet Summary', "route" => '' ],
                        // [ "id" => 17, "name" => 'Business Snapshot', "route" => '' ],
                        [
                            "id" => 18,
                            "name" => __('Profit and loss statement'),
                            "alias" => 'Profit and loss statement',
                            "route" => '/app/reports/report/profit-loss'],
                        // [ "id" => 19, "name" => 'Profit and Loss as % of total income', "route" => '' ],
                        // [ "id" => 111, "name" => 'Profit and Loss Comparison', "route" => '' ],
                        // [ "id" => 112, "name" => 'Profit and Loss Detail', "route" => '' ],
                        // [
                        //   "id" => 113,
                        //   "name" => 'Profit and Loss year-to-date comparison',
                        //   "route" => '',
                        // ],
                        // [ "id" => 114, "name" => 'Profit and Loss by Customer', "route" => '' ],
                        // [ "id" => 115, "name" => 'Profit and Loss by Month', "route" => '' ],
                        // [ "id" => 116, "name" => 'Profit and Loss by Tag Group', "route" => '' ],
                        // [ "id" => 117, "name" => 'Project Profitability Summary', "route" => '' ],
                        // [ "id" => 118, "name" => 'Quarterly Profit and Loss Summary', "route" => '' ],
                    ],
                ],
                [
                    "id" => 2,
                    "name" => __('Who owe you'),
                    "alias" => 'Who owe you',
                    "children" => [
                        // [ "id" => 21, "name" => 'Account receivable aging detail', "route" => '' ],
                        // [ "id" => 22, "name" => 'Account receivable aging summary', "route" => '' ],
                        // [ "id" => 23, "name" => 'Collections Report', "route" => '' ],
                        // [ "id" => 24, "name" => 'Customer Balance Detail', "route" => '' ],
                        [
                            "id" => 25,
                            "name" => __('Customer Balance Summary'),
                            "alias" => 'Customer Balance Summary',
                            "route" => '/app/reports/report/customer-balance'],
                        // [ "id" => 26, "name" => 'Invoice List', "route" => '/app/reports/report/invoice' ],
                        // [ "id" => 27, "name" => 'Invoices and Received Payments', "route" => '' ],
                        [
                            "id" => 28,
                            "name" => __('Open invoice'),
                            "alias" => 'Open invoice',
                            "route" => '/app/reports/report/open-invoice'
                        ],
                        // [ "id" => 29, "name" => 'Statement List', "route" => '' ],
                        // [ "id" => 211, "name" => 'Terms List', "route" => '' ],
                        // [ "id" => 212, "name" => 'Unbilled charges', "route" => '' ],
                        // [ "id" => 213, "name" => 'Unbilled time', "route" => '' ],
                    ],
                ],
                [
                    "id" => 4,
                    "name" => __('Sales and customer'),
                    "alias" => 'Sales and customer',
                    "children" => [
                        // [ "id" => 41, "name" => 'Customer Contact List', "route" => '' ],
                        // [ "id" => 42, "name" => 'Deposit Detail', "route" => '' ],
                        // [
                        //   "id" => 43,
                        //   "name" => 'Estimates & Progress Invoicing Summary by Customer',
                        //   "route" => '',
                        // ],
                        // [ "id" => 44, "name" => 'Estimates by Customer', "route" => '' ],
                        [
                            "id" => 45,
                            "name" => __('Income by Customer Summary'),
                            "alias" => 'Income by Customer Summary',
                            "route" => '/app/reports/report/customer-invoice'
                        ],
                        // [ "id" => 46, "name" => 'Inventory Valuation Detail', "route" => '' ],
                        [
                            "id" => 47,
                            "name" => __('Inventory Valuation Summary'),
                            "alias" => 'Inventory Valuation Summary',
                            "route" => '/app/reports/report/inventory-valuation'
                        ],
                        // [ "id" => 48, "name" => 'Payment Method List', "route" => '' ],
                        // [ "id" => 49, "name" => 'Physical Inventory Worksheet', "route" => '' ],
                        [
                            "id" => 411,
                            "name" => __('Product/Service List'),
                            "alias" => 'Product/Service List',
                            "route" => '/app/reports/report/product'
                        ],
                        // [ "id" => 412, "name" => 'Sales by Class Detail', "route" => '' ],
                        // [ "id" => 413, "name" => 'Sales by Class Summary', "route" => '' ],
                        // [ "id" => 414, "name" => 'Sales by Customer Detail', "route" => '' ],
                        [
                            "id" => 415,
                            "name" => __('Sales by Customer Summary'),
                            "alias" => 'Sales by Customer Summary',
                            "route" => '/app/reports/report/sales-customer'
                        ],
                        // [ "id" => 416, "name" => 'Sales by Customer Type Detail', "route" => '' ],
                        // [ "id" => 417, "name" => 'Sales by Location Detail', "route" => '' ],
                        // [ "id" => 418, "name" => 'Sales by Location Summary', "route" => '' ],
                        // [ "id" => 419, "name" => 'Sales by Product/Service Detail', "route" => '' ],
                        [
                            "id" => 421,
                            "name" => __('Sales by Product/Service Summary'),
                            "alias" => 'Sales by Product/Service Summary',
                            "route" => '/app/reports/report/sales-product'
                        ],
                        // [ "id" => 422, "name" => 'Time Activities by Customer Detail', "route" => '' ],
                        [
                            "id" => 423,
                            "name" => __('Transaction List by Customer'),
                            "alias" => 'Transaction List by Customer',
                            "route" => '/app/reports/report/transaction-customer'
                        ],
                        // [ "id" => 424, "name" => 'Transaction List by Tag Group', "route" => '' ],
                    ],
                ],
                [
                    "id" => 5,
                    "name" => __('What you owe'),
                    "alias" => 'What you owe',
                    "children" => [
                        // [ "id" => 51, "name" => 'Accounts payable aging detail', "route" => '' ],
                        [
                            "id" => 52,
                            "name" => __('Accounts payable aging summary'),
                            "alias" => 'Accounts payable aging summary',
                            "route" => '/app/reports/report/payable'
                        ],
                        [
                            "id" => 53,
                            "name" => __('Bill Payment List'),
                            "alias" => 'Bill Payment List',
                            "route" => '/app/reports/report/vendor-payment'
                        ],
                        // [ "id" => 54, "name" => 'Bills and Applied Payment', "route" => '' ],
                        [
                            "id" => 55,
                            "name" => __('Unpaid Bills'),
                            "alias" => 'Unpaid Bills',
                            "route" => '/app/reports/report/vendor-unpaid'
                        ],
                        // [ "id" => 56, "name" => 'Vendor Balance Detail', "route" => '' ],
                        [
                            "id" => 57,
                            "name" => __('Vendor Balance Summary'),
                            "alias" => 'Vendor Balance Summary',
                            "route" => '/app/reports/report/vendor'
                        ],
                    ],
                ],
                [
                    "id" => 6,
                    "name" => __('Expense and vendor'),
                    "alias" => 'Expense and vendor',
                    "children" => [
                        [
                            "id" => 61,
                            "name" => __('Expenses by Vendor Summary'),
                            "alias" => 'Expenses by Vendor Summary',
                            "route" => '/app/reports/report/expense'
                        ],
                        [
                            "id" => 62,
                            "name" => 'Open Purchase Order List',
                            "route" => '/app/reports/report/open-purchase'
                        ],
                        // [ "id" => 63, "name" => 'Open Purchase Order Detail', "route" => '' ],
                        [
                            "id" => 64,
                            "name" => __('Purchases by Product/Service Detail'),
                            "alias" => 'Purchases by Product/Service Detail',
                            "route" => '/app/reports/report/purchase-product'
                        ],
                        // [ "id" => 65, "name" => 'Purchases by Vendor Detail', "route" => '' ],
                        [
                            "id" => 66,
                            "name" => __('Transaction List by Vendor'),
                            "alias" => 'Transaction List by Vendor',
                            "route" => '/app/reports/report/transaction-vendor'
                        ],
                        // [ "id" => 67, "name" => 'Vendor Contact List', "route" => '' ],
                    ],
                ],
                [
                    "id" => 7,
                    "name" => __('Payroll'),
                    "alias" => 'Payroll',
                    "children" => [
                        [
                            "id" => 71,
                            "name" => __('Employee Contact List'),
                            "alias" => 'Employee Contact List',
                            "route" => '/app/reports/report/employee'
                        ],
                        [
                            "id" => 72,
                            "name" => __('Paycheck History'),
                            "alias" => 'Paycheck History',
                            "route" => '/app/reports/report/paycheck'
                        ],
                        [
                            "id" => 73,
                            "name" => __('Payroll Billing Summary'),
                            "alias" => 'Payroll Billing Summary',
                            "route" => '/app/reports/report/payroll'
                        ],
                        // [ "id" => 74, "name" => 'Payroll Details', "route" => '' ],
                        // [ "id" => 75, "name" => 'Payroll Summary by Employee', "route" => '' ],
                        // [ "id" => 76, "name" => 'Payroll Tax Liability', "route" => '' ],
                        // [ "id" => 77, "name" => 'Payroll Tax Payments', "route" => '' ],
                        // [ "id" => 78, "name" => 'Time Activities by Employee Detail', "route" => '' ],
                        // [ "id" => 79, "name" => 'Total Pay', "route" => '' ],
                        // [ "id" => 711, "name" => 'Total Payroll Cost', "route" => '' ],
                        // [ "id" => 712, "name" => 'Vacation and Sick Leave', "route" => '' ],
                    ],
                ],

                [
                    "id" => 8,
                    "name" => __('Accounting'),
                    "alias" => 'Accounting',
                    "children" => [
                        [
                            "id" => 81,
                            "name" => __('Account List'),
                            "alias" => 'Account List',
                            "route" => '/app/reports/report/account'
                        ],
                        // [ "id" => 82, "name" => 'Balance Sheet Comparison', "route" => '' ],
                        [
                            "id" => 83,
                            "name" => __('Balance Sheet'),
                            "alias" => 'Balance Sheet',
                            "route" => '/app/reports/report/balance-sheet'
                        ],
                        [
                            "id" => 84,
                            "name" => __('General Ledger'),
                            "alias" => 'General Ledger',
                            "route" => '/app/reports/report/gl'
                        ],
                        [
                            "id" => 85,
                            "name" => __('Journal Entry'),
                            "alias" => 'Journal Entry',
                            "route" => '/app/reports/report/journal'
                        ],
                        // [ "id" => 86, "name" => 'Profit and Loss Comparison', "route" => '' ],
                        // [ "id" => 87, "name" => 'Profit and Loss by Tag Group', "route" => '' ],
                        [
                            "id" => 88,
                            "name" => __('Profit and loss statement'),
                            "alias" => 'Profit and loss statement',
                            "route" => '/app/reports/report/profit-loss'
                        ],
                        // [ "id" => 89, "name" => 'Recent Automatic Transactions', "route" => '' ],
                        [
                            "id" => 811,
                            "name" => __('Recent Transactions'),
                            "alias" => 'Recent Transactions',
                            "route" => '/app/reports/report/transaction'
                        ],
                        // [ "id" => 812, "name" => 'Reconciliation Reports', "route" => '' ],
                        [
                            "id" => 813,
                            "name" => __('Statement of cash flow'),
                            "alias" => 'Statement of cash flow',
                            "route" => '/app/reports/report/cash-flow'
                        ],
                        // [ "id" => 814, "name" => 'Transaction Detail by Account', "route" => '' ],
                        // [ "id" => 815, "name" => 'Transaction List by Date', "route" => '' ],
                        // [ "id" => 816, "name" => 'Transaction List with Splits', "route" => '' ],
                        [
                            "id" => 817,
                            "name" => __('Trial Balance'),
                            "alias" => 'Trial Balance',
                            "route" => '/app/reports/report/trial-balance'
                        ],
                        [
                            "id" => 818,
                            "name" => __('Account Balance'),
                            "alias" => 'Account Balance',
                            "route" => '/app/reports/report/account-balance'
                        ],
                    ],
                ],
            ]
        ]);
    }
}
