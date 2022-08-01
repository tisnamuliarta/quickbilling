<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ReceiptLine;
use App\Models\Inventory\ReceiptProduction;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class IssueService
{
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
            'account_id' => $account_id,
            'main_account_amount' => $document->main_account_amount,
            'reference_no' => $document->transaction_no,
            'narration' => $document->item_name,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
        ];
        // create production receipt
        $issue = ReceiptProduction::create($header);
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
            'base_num' => $issue->transaction_no
        ]);

        $item = [
            'production_id' => $issue->id,
            'entity_id' => $issue->entity_id,
            'item_id' => $document->item_id,
            'quantity' => $issue->planned_qty,
            'warehouse_id' => $document->warehouse_id,
            'price' => $document->commission_rate,
            'amount' => $document->commission_rate,
            'account_id' => $account_line,
            'item_type' => '-',
            'item_name' => '-',
            'unit' => '-',
            //'unit' => $line_item['unit'],
            'narration' => 'receipt from production',
            'sub_total' => floatval($document->commission_rate) * $document->planned_qty
        ];

        $line = ReceiptLine::create($item);

        $journalEntry->addLineItem(
            LineItem::create([
                'item_id' => $document->item_id,
                'account_id' => $line->account_id,
                'description' => $line->item_name,
                'amount' => $document->commission_rate,
                'quantity' => $document->planned_qty,
                'base_line_id' => $line->id,
                'transaction_id' => $journalEntry->id
            ])
        );
        $journalEntry->post();
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
        $type = 'PI';
        $header = [
            'entity_id' => $document->entity_id,
            'transaction_no' => $this->generateDocNum(Carbon::now(), $type),
            'transaction_type' => $type,
            'transaction_date' => date('Y-m-d'),
            'account_id' => $account_id,
            'main_account_amount' => $document->main_account_amount,
            'reference_no' => $document->transaction_no,
            'narration' => $document->item_name,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            //'base_num' => $document->transaction_no
        ];

        $issue = ReceiptProduction::create($header);

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
            'base_num' => $issue->transaction_no
        ]);

        foreach ($document->lineItems as $line_item) {
            $item = [
                'production_id' => $line_item->production_id,
                'entity_id' => $line_item->entity_id,
                'item_id' => $line_item->item_id,
                'quantity' => $line_item->base_qty,
                'warehouse_id' => $line_item->warehouse_id,
                'price' => $line_item->price,
                'amount' => $line_item->amount,
                'account_id' => $line_item->account_id,
                'item_type' => $line_item->item_type,
                'item_name' => $line_item->item_name,
                'unit' => $line_item->unit,
                'narration' => $line_item->item_name,
                'sub_total' => floatval($line_item->amount) * $line_item->base_qty
            ];

            $line = ReceiptLine::create($item);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line->account_id,
                    'description' => $line->item_name,
                    'amount' => $line->amount,
                    'quantity' => $line->quantity,
                    'item_id' => $line->item_id,
                    'base_line_id' => $line->id,
                    'transaction_id' => $journalEntry->id
                ])
            );
        }
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
