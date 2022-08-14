<?php

namespace App\Services\Inventory;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use App\Models\Inventory\ReceiptProduction;
use App\Models\Payroll\EmployeeCommission;
use App\Services\Financial\AccountMappingService;
use App\Traits\ApiResponse;
use App\Traits\InventoryHelper;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class IssueService
{
    use InventoryHelper;
    use ApiResponse;

    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int)$request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'transaction_no';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $query = ReceiptProduction::select('*')
            ->where('transaction_no', 'LIKE', '%' . $search . '%')
            ->where('transaction_type', 'issue')
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        return $query;
    }

    /**
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($request, $type): array
    {
        $data = $request->all();

        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'destroyed_at');
        Arr::forget($data, 'id');
        Arr::forget($data, 'line_items');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'sub_total');
        Arr::forget($data, 'item_name');
        Arr::forget($data, 'commission_rate');
        Arr::forget($data, 'account');
        Arr::forget($data, 'item');
        Arr::forget($data, 'sub_total');

        return $data;
    }

    /**
     * @param $document
     * @param $account_line
     * @param $narration
     * @param $account_id
     * @return void
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function processReceipt($document, $account_line, $narration, $account_id)
    {
        $type = 'PR';
        $header = [
            'entity_id' => $document->entity_id,
            'transaction_no' => $this->generateDocNum(Carbon::now(), $type),
            'transaction_type' => $type,
            'transaction_date' => date('Y-m-d'),
            //'account_id' => $account_id,
            'main_account_amount' => $document->main_account_amount,
            'reference_no' => $document->transaction_no,
            'narration' => $document->item_name,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'status' => 'closed',
            'due_date' => $document->due_date,
            'currency_code' => auth()->user()->entity->currency->currency_code,
            'currency_rate' => 0,
            'contact_id' => 0,
            'contact_name' => '',
            'contact_email' => '',
            'contact_tax_number' => '',
            'contact_phone' => '',
            'contact_zip_code' => '',
            'contact_city' => '',
        ];
        // create production receipt
        $issue = Document::create($header);

        // production receipt items
        $item = [
            'document_id' => $issue->id,
            'entity_id' => $issue->entity_id,
            'item_id' => $document->item_id,
            'quantity' => $document->planned_qty,
            'warehouse_id' => $document->warehouse_id,
            'price' => $document->commission_rate,
            'amount' => $document->commission_rate,
            //'account_id' => $account_line,
            'type' => $type,
            'name' => '-',
            'unit' => '-',
            //'unit' => $line_item['unit'],
            'narration' => 'receipt from production',
            'sub_total' => floatval($document->commission_rate) * $document->planned_qty
        ];

        $line = DocumentItem::create($item);

        // post journal production receipt
        $journalEntry = JournalEntry::create([
            'account_id' => $account_id,
            'date' => Carbon::now(),
            'narration' => $narration . $document->transaction_no,
            'reference' => $issue->transaction_no,
            'credited' => true, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'status' => 'closed',
            'base_id' => $issue->id,
            'base_type' => $issue->transaction_type,
            'base_num' => $issue->transaction_no,
            'created_by' => auth()->user()->id
        ]);

        $sum_direct_labor = 0;
        $sum_direct_material = 0;

        foreach ($document->lineItems as $line_item) {
            if ($line_item->item_type == 'resource' && $line_item->item->resource_type == 'labor') {
                $sub_total = $line_item->base_qty * $line_item->amount;
                $sum_direct_labor = $sum_direct_labor + $sub_total;
            } else {
                $sub_total = floatval($line_item->amount) * $line_item->base_qty;
                $sum_direct_material = $sum_direct_material + $sub_total;

                $item_warehouse = $this->getItemWarehouse($line_item->item_id, $line_item->warehouse_id);
                $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $line_item->base_qty;
                $item_warehouse->save();
            }
        }

        $journalEntry->addLineItem(
            LineItem::create([
                'item_id' => $document->item_id,
                'account_id' => $account_line,
                'description' => 'receipt from production',
                'narration' => 'receipt from production',
                'amount' => $sum_direct_labor + $sum_direct_material,
                'base_line_id' => $line->id,
                'transaction_id' => $journalEntry->id,
                'created_by' => auth()->user()->id
            ])
        );
        $journalEntry->post();

        $item = $document->item_id;
        $warehouse = $document->warehouse_id;

        // get item warehouse
        $item_warehouse = $this->getItemWarehouse($item, $warehouse);
        $prev_cost = round(floatval($item_warehouse->item_cost), 2);

        // increase stock
        $temp_cost = round($document->main_account_amount, 2);
        $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $document->planned_qty;
        $item_warehouse->save();

        // calculate cost
        $item_warehouse = $this->getItemWarehouse($item, $warehouse);
        $item_cost = round(($temp_cost + $prev_cost) / $item_warehouse->available_qty, 2);
        $item_warehouse->item_cost = $item_cost;
    }

    /**
     * @param $sysDate
     * @param string $type
     * @return string
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate, string $type): string
    {
        $alias = $type;
        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = ReceiptProduction::where('transaction_date', '>=', $periodStart)
            ->where('transaction_type', $type)
            ->count();

        $nextId = $nextId + 1;

        return $alias . '-' . str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @param $document
     * @param $line_items
     * @param $narration
     * @param $account_id
     * @return void
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function processIssue($document, $line_items, $narration, $account_id)
    {
        $accountMapping = new AccountMappingService();

        $type = 'PI';
        $header = [
            'entity_id' => $document->entity_id,
            'transaction_no' => $this->generateDocNum(Carbon::now(), $type),
            'transaction_type' => $type,
            'transaction_date' => date('Y-m-d'),
            //'account_id' => $account_id,
            'main_account_amount' => $document->main_account_amount,
            'reference_no' => $document->transaction_no,
            'narration' => $document->item_name,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'status' => 'closed',
            'due_date' => $document->due_date,
            'currency_code' => auth()->user()->entity->currency->currency_code,
            'currency_rate' => 0,
            'contact_id' => 0,
            'contact_name' => '',
            'contact_email' => '',
            'contact_tax_number' => '',
            'contact_phone' => '',
            'contact_zip_code' => '',
            'contact_city' => '',
        ];

        $issue = Document::create($header);

        $sum_direct_labor = 0;
        $sum_direct_material = 0;

        $payroll_clearing = $accountMapping->getAccountByName('Payroll Clearing')->account_id;

        foreach ($document->lineItems as $line_item) {
            if ($line_item->item_type == 'resource' && $line_item->item->resource_type == 'labor') {
                $commission = EmployeeCommission::create([
                    'employee_id' => $line_item->item->employee_id,
                    'transaction_id' => $issue->id,
                    'account_id' => $payroll_clearing,
                    'line_item_id' => $line_item->id,
                    'transaction_type' => $type,
                    'transaction_date' => $issue->transaction_date,
                    'quantity' => $line_item->base_qty,
                    'amount' => $line_item->amount,
                    'status' => 'open'
                ]);

                $sum_direct_labor = $sum_direct_labor + $commission->sub_total;
            } else {
                $sub_total = floatval($line_item->amount) * $line_item->base_qty;
                $sum_direct_material = $sum_direct_material + $sub_total;
            }

            $item = [
                'document_id' => $issue->id,
                'entity_id' => $line_item->entity_id,
                'item_id' => $line_item->item_id,
                'type' => $line_item->item_type,
                'quantity' => $line_item->base_qty,
                'warehouse_id' => $line_item->warehouse_id,
                'price' => $line_item->price,
                'amount' => $line_item->amount,
                //'account_id' => $line_item->account_id,
                //'item_type' => $line_item->item_type,
                'name' => $line_item->item_name,
                'unit' => $line_item->unit,
                'narration' => $line_item->item_name,
                'sub_total' => floatval($line_item->amount) * $line_item->base_qty
            ];

            $line = DocumentItem::create($item);
        }

        // create journal entry for debit WIP account
        $journalEntry = JournalEntry::create([
            'account_id' => $account_id,
            'date' => Carbon::now(),
            'narration' => $narration . $document->transaction_no,
            'reference' => $issue->transaction_no,
            'credited' => false, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'status' => 'closed',
            'base_id' => $issue->id,
            'base_type' => $issue->transaction_type,
            'base_num' => $issue->transaction_no,
            'created_by' => auth()->user()->id
        ]);

        $journalEntry->addLineItem(
            LineItem::create([
                'account_id' => $document->item->inventory_account,
                'description' => 'Issue for production',
                'narration' => 'Issue for production',
                'amount' => $sum_direct_labor + $sum_direct_material,
                'created_by' => auth()->user()->id
            ])
        );

        $journalEntry->post();
    }

    /**
     * @param $type
     * @return array
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function getForm($type): array
    {
        $form = $this->form('receipt_productions');
        $form['transaction_type'] = $type;
        $form['transaction_date'] = Carbon::now()->format('Y-m-d');
        $form['due_date'] = Carbon::now()->format('Y-m-d');
        $form['status'] = 'open';
        $form['line_items'] = [];
        $form['id'] = 0;
        $form['transaction_no'] = $this->generateDocNum(Carbon::now(), $type);

        return $form;
    }
}
