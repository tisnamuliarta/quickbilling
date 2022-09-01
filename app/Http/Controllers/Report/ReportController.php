<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Contact;
use App\Models\Inventory\InventoryTransaction;
use App\Models\Inventory\ItemWarehouse;
use App\Models\Reports\AccountSchedule;
use App\Models\Settings\Entity;
use App\Services\Reports\ReportService;
use Carbon\Carbon;
use IFRS\Models\Account;
use IFRS\Reports\AccountStatement;
use IFRS\Reports\BalanceSheet;
use IFRS\Reports\CashFlowStatement;
use IFRS\Reports\IncomeStatement;
use IFRS\Reports\TrialBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
     * @return JsonResponse|void
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

            case strtoupper('Inventory Valuation Detail'):
                $inventoryValuation = InventoryTransaction::whereBetween('transaction_date', [$start_date, $end_date])
                    ->orderBy('item_id')
                    ->get();

                return $this->success([
                    'data' => view('reports.inventory_valuation', compact('inventoryValuation'))->render(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Inventory Valuation Summary'):
                $start_date = $start_date . ' 00:00:01';
                $end_date = $end_date . ' 23:59:00';
                $inventoryValuation = ItemWarehouse::whereBetween('updated_at', [$start_date, $end_date])
                    ->orderBy('item_id')
                    ->get();

                return $this->success([
                    'data' => view('reports.inventory_valuation_detail', compact('inventoryValuation'))->render(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);

            case strtoupper('Customer Balance Summary'):
                $customers = Contact::where('type', 'Customer')->get();
                $customer_account = [];
                foreach ($customers as $customer) {
                    $account = Account::find($customer->receivable_account_id);
//                    $schedule = new AccountSchedule(
//                        $customer->receivable_account_id
//                    );
//                    if ($schedule->getTransactions()) {
//                        $customer_account[] = $schedule->getTransactions();
//                    }
                    $transaction = $account->getTransactions($start_date, $end_date);
                    if ($transaction['transactions']) {
                        $customer_account[] = Arr::add($transaction, 'customer', $customer->name);
                    }
                }

                return $this->success([
//                    'data' => $customer_account,
                    'data' => view('reports.customer_balance', compact('customer_account'))->render(),
                    'start_date' => Carbon::parse($start_date)->isoFormat('DD MMMM Y'),
                    'end_date' => Carbon::parse($end_date)->isoFormat('DD MMMM Y'),
                ]);
        }

        throw new \Exception('No report match');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        App::setLocale(auth()->user()->locale);

        return $this->success([
            'data' => $this->service->reportList()
        ]);
    }
}
