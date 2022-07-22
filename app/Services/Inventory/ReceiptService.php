<?php

namespace App\Services\Inventory;

use App\Models\Inventory\ReceiptLine;
use App\Models\Inventory\ReceiptProduction;
use App\Services\Financial\AccountMappingService;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ReceiptService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'transaction_no';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $query = ReceiptProduction::select('*')
            ->where('transaction_no', 'LIKE', '%'.$search.'%')
            ->where('transaction_type', 'receipt')
            ->with(['lineItems', 'lineItems.account', 'account'])
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

        return $data;
    }

    /**
     * @param $sysDate
     * @return string
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate): string
    {
        $alias = 'GP';
        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = ReceiptProduction::where('transaction_date', '>=', $periodStart)
            ->where('transaction_type', 'receipt')
            ->count();

        $nextId = $nextId + 1;

        return $alias.'-'.str_pad((string) $periodCount, 2, '0', STR_PAD_LEFT)
            .$month.
            str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function createIssue($base_doc, $line_items)
    {
        $accountMapping = new AccountMappingService();
        $header = [
            'transaction_no' => $this->generateDocNum(Carbon::now()),
            'transaction_type' => 'issue',
            'transaction_date' => date('Y-m-d'),
            'account_id' => $accountMapping->getAccountByName('WIP Inventory Account')->account_id,
            'main_account_amount' => $base_doc->main_account_amount,
            'reference_no' => $base_doc->transaction_no
        ];

        $issue = ReceiptProduction::create($header);

        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('WIP Inventory Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Compound Journal Entry",
            'credited' => false, // main account should be debited
            'main_account_amount' => $base_doc->main_account_amount
        ]);

        foreach ($line_items as $line_item) {
            $item = [
                'production_id' => $issue->id,
                'entity_id' => $line_item['entity_id'],
                'item_id' => $line_item['item_id'],
                'quantity' => $line_item['base_qty'],
                'warehouse_id' => $line_item['warehouse_id'],
                'price' => $line_item['price'],
                'amount' => $line_item['amount'],
                'account_id' => $line_item['account_id'],
                'item_type' => $line_item['item_type'],
                'item_name' => $line_item['item_name'],
                'uom' => $line_item['uom'],
                'narration' => $line_item['item_name'],
            ];

            $line = ReceiptLine::create($item);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line->account_id,
                    'description' => $line->item_name,
                    'amount' => $line->amount,
                    'credited' => true
                ])
            );
        }
    }
}
