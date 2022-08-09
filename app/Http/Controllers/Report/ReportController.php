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
                return $this->success(['data' => $report->getTransactions()]);

            case strtoupper('Profit and Loss'):
                $report = new IncomeStatement($start_date, $end_date, $entity);
                //$data = $this->service->transformProfitAndLoss($report->getSections());
                return $this->success([
                    'data' =>  collect($report->getSections($start_date, $end_date)),
                    'start_date' => Carbon::parse($start_date)->format('M d Y'),
                    'end_date' => Carbon::parse($end_date)->format('M d Y'),
                ]);
            //return $this->success(['data' => $report->getResults(date('m'), date('Y'), $entity)]);

            case strtoupper('Balance sheet'):
                $report = new BalanceSheet($end_date, $entity);
                return $this->success(['data' => $report->getSections()]);

            case strtoupper('Trial Balance'):
                $report = new TrialBalance($end_date, $entity);
                return $this->success(['data' => $report->getSections()]);

            case strtoupper('Statement of cash flow'):
            case strtoupper('Statement of cash flows'):
                $report = new CashFlowStatement($start_date, $end_date, $entity);
                return $this->success(['data' => $report->getSections()]);

            case strtoupper('Product/Service List'):
                $report = $this->service->getItemList();
                return $this->success(['data' => $report]);
        }

        throw new \Exception('No report match');
    }
}
