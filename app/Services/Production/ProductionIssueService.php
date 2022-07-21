<?php

namespace App\Services\Production;

use App\Models\Inventory\ItemUnit;
use App\Models\Inventory\ReceiptProduction;
use App\Models\Productions\Production;
use App\Models\Productions\ReceiptLine;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ProductionIssueService
{
    use ApiResponse;
    /**
     * @param $request
     * @return array
     */
    public function index($request)
    {
        $pages = isset($request->page) ? (int) $request->page : 1;
        $row_data = isset($request->itemsPerPage) ? (int) $request->itemsPerPage : 1000;
        $sorts = isset($request->sortBy[0]) ? (string) $request->sortBy[0] : 'name';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = $request->search;
        $offset = ($pages - 1) * $row_data;

        $query = ReceiptProduction::select('*')
            ->where('name', 'LIKE', '%'.$search.'%')
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

        return $data;
    }

    /**
     * @param $sysDate
     * @return string
     */
    protected function generateDocNum($sysDate): string
    {
        $doc_num = ReceiptProduction::selectRaw('code')
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '00000' : $doc_num->code;
        $clear_doc_num = (int) substr($number, 0, 5);
        $number = $clear_doc_num + 1;

        return sprintf('%05s', $number);
    }

    /**
     * @param $items
     * @param $document
     * @param $tax_details
     * @return void
     */
    public function processItems($items, $document, $tax_details)
    {
        foreach ($items as $item) {
            if (array_key_exists('id', $item) && $item['id']) {
                $item_detail = ReceiptLine::find($item['id']);
                $forms = $this->detailsForm($document, $item, 'update');
                foreach ($forms as $index => $form) {
                    $item_detail->$index = $form;
                }
                $item_detail->save();
            } else {
                $item_detail = ReceiptLine::create($this->detailsForm($document, $item, 'store'));
            }
        }
    }

    /**
     * @param $type
     * @return array
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function getForm($type): array
    {
        $form = $this->form('documents');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['price_include_tax'] = false;
        $form['transaction_type'] = $type;
        $form['payment_term_id'] = 1;
        $form['discount_type'] = 'Percent';
        $form['withholding_type'] = 'Percent';
        $form['status'] = 'draft';
        $form['tax_details'] = [];
        $form['line_items'] = [];
        $form['shipping_fee'] = 0;
        $form['category_id'] = 0;
        $form['parent_id'] = 0;
        $form['currency_rate'] = 0;
        $form['id'] = 0;
        $form['transaction_no'] = $this->generateDocNum(Carbon::now(), $type);
        $form['temp_id'] = mt_rand(100000, 999999999999);

        return $form;
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
            'type' => $document->transaction_type,
            'document_id' => $document->id,
            'item_id' => $item['item_id'],
            'name' => $item['name'],
            'narration' => $item['narration'],
            'sku' => $item['sku'],
            'quantity' => floatval($item['quantity']),
            'price' => floatval($item['price']),
            'unit' => $item['unit'],
            'tax_name' => (array_key_exists('tax_name', $item)) ? $item['tax_name'] : null,
            'vat_id' => (array_key_exists('tax_name', $item)) ? $this->getTaxIdByName($item['tax_name']) : 0,
            'warehouse_id' => (array_key_exists('whs_name', $item)) ? $this->getWhsIdByName($item['whs_name']) : 0,
            'discount_rate' => floatval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'amount' => floatval($item['amount']),
        ];

        if ($document->base_id && $type == 'store') {
            $merge['base_line_id'] = $item['id'];
            $form = array_merge($form, $merge);
        }

        $merge = [];
        if ($type == 'store') {
            $merge['created_by'] = auth()->user()->id;
            $form = array_merge($form, $merge);
        }

        return $form;
    }
}
