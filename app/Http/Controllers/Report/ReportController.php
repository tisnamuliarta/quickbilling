<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Settings\Entity;
use App\Services\Reports\ReportService;
use Carbon\Carbon;
use IFRS\Reports\AccountStatement;
use IFRS\Reports\BalanceSheet;
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
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \IFRS\Exceptions\MissingAccount
     * @throws \Exception
     */
    public function preview(Request $request)
    {
        $report_type = $request->report_type;
        $account_id = $request->account_id;
        $start_date = (isset($request->start_date)) ? $request->start_date : Carbon::now()->addDays(-360)
            ->format('Y-m-d');
        $end_date = (isset($request->end_date)) ? $request->end_date : Carbon::now()->format('Y-m-d');

        $entity = Entity::find($request->user()->entity_id);

        switch ($report_type) {
            case 'Account Balance':
                $report = new AccountStatement($account_id, null, $start_date, $end_date);
                return $this->success(['data' => $report->getTransactions()]);

            case 'Profit and Loss':
                $report = new IncomeStatement($start_date, $end_date, $entity);
                //$data = $this->service->transformProfitAndLoss($report->getSections());
                return $this->success(['data' => $report->getSections()]);

            case 'Balance sheet':
                $report = new BalanceSheet($end_date, $entity);
                return $this->success(['data' => $report->getSections()]);

            case 'Trial Balance':
                $report = new TrialBalance($end_date, $entity);
                return $this->success(['data' => $report->getSections()]);

            case 'Product/Service List':
                $report = $this->service->getItemList();
                return $this->success(['data' => $report]);
        }

        throw new \Exception('No report match');
    }
}
