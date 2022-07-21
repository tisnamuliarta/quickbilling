<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use IFRS\Reports\AccountStatement;
use IFRS\Reports\BalanceSheet;
use IFRS\Reports\IncomeStatement;
use IFRS\Reports\TrialBalance;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @throws \IFRS\Exceptions\MissingAccount
     */
    public function preview(Request $request)
    {
        $report_type = $request->report_type;
        $account_id = $request->account_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        switch ($report_type) {
            case 'Account Balance':
                $report = new AccountStatement($account_id, null, $start_date, $end_date);
                return $report->getTransactions();

            case 'Profit and loss':
                $report = new IncomeStatement($start_date, $end_date, $request->user()->entity_id);
                return $report->getSections();

            case 'Balance Sheet':
                $report = new BalanceSheet($end_date, $request->user()->entity_id);
                return $report->getSections();

            case 'Trial Balance':
                $report = new TrialBalance($end_date, $request->user()->entity_id);
                return $report->getSections();
        }
    }
}
