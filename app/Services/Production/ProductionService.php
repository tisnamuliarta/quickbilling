<?php

namespace App\Services\Production;

use App\Models\Inventory\ReceiptLine;
use App\Models\Inventory\ReceiptProduction;
use App\Models\Productions\Production;
use App\Models\Productions\ProductionItem;
use App\Services\Financial\AccountMappingService;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductionService
{
    use ApiResponse;
    use Financial;

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

        $query = Production::select('*')
            ->where('transaction_no', 'LIKE', '%' . $search . '%')
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

        if ($type == 'store') {
            $data['main_account_amount'] = $data['sub_total'];
        }

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
     * @param $items
     * @param $document
     * @return void
     */
    public function processItems($items, $document)
    {
        foreach ($items as $item) {
            if (array_key_exists('id', $item) && $item['id']) {
                $item_detail = ProductionItem::find($item['id']);
                $forms = $this->detailsForm($document, $item, 'update');
                foreach ($forms as $index => $form) {
                    $item_detail->$index = $form;
                }
                $item_detail->save();
            } else {
                $item_detail = ProductionItem::create($this->detailsForm($document, $item, 'store'));
            }
        }
    }

    /**
     * @param $document
     * @param $item
     * @param $type
     * @return array
     */
    protected function detailsForm($document, $item, $type): array
    {
        $form = [
            'entity_id' => $document->entity_id,
            'production_id' => $document->id,
            'item_id' => $item['item_id'],
            'item_name' => (array_key_exists('item_id', $item)) ? $this->getResourceById($item['item_id'])->name : '',
            'account_id' => (array_key_exists('item_id', $item))
                ? $this->getResourceById($item['item_id'])->account_id : '',
            'item_type' => 'resource',
            'narration' => $item['narration'],
            'base_qty' => floatval($item['base_qty']),
            'planned_qty' => floatval($document->planned_qty) * floatval($item['base_qty']),
            'price' => floatval($item['price']),
            'unit' => $item['unit'],
            'warehouse_id' => (array_key_exists('whs_code', $item)) ? $this->getWhsIdByName($item['whs_code']) : 0,
            'amount' => floatval($item['amount']),
            'issue_method' => 'backflush',
        ];

        if ($document->base_id && $type == 'store') {
            $merge['base_line_id'] = $item['id'];
            $form = array_merge($form, $merge);
        }

        $merge = [];
        if ($type == 'store') {
            $merge['pick_status'] = 'not picked';
            // $merge['issue_method'] = 'backflush';
            $merge['start_date'] = $document->transaction_date;
            $form = array_merge($form, $merge);
        }

        return $form;
    }

    /**
     * @param $type
     * @return array
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function getForm($type): array
    {
        $form = $this->form('productions');
        $form['transaction_type'] = $type;
        $form['transaction_date'] = Carbon::now()->format('Y-m-d');
        $form['due_date'] = Carbon::now()->format('Y-m-d');
        $form['status'] = 'planned';
        $form['line_items'] = [];
        $form['id'] = 0;
        $form['transaction_no'] = $this->generateDocNum(Carbon::now(), $type);

        return $form;
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $alias = Str::limit($alias, 2);

        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = Production::where('transaction_date', '>=', $periodStart)
            ->where('transaction_type', $alias)
            ->count();

        $nextId = $nextId + 1;

        return $alias . '-' . str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
    }
}
