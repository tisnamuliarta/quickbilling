<?php

namespace App\Services\Transactions;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use App\Models\Documents\DocumentItemTax;
use App\Models\Sales\SalesPerson;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use IFRS\Models\ReportingPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService
{
    use ApiResponse;
    use Financial;

    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $type = (isset($request->type)) ? $request->type : '';
        $options = $request->options;
        $pages = isset($options->page) ? (int)$options->page : 1;
        $row_data = isset($options->itemsPerPage) ? (int)$options->itemsPerPage : 10;
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : 'document_number';
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : 'desc';
        $offset = ($pages - 1) * $row_data;

        $model = $this->mappingTable($type);
        $result = [];
        $query = $model::with(['entity', 'lineItems']);

        $result['total'] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $result['form'] = $this->getForm($type);

        return array_merge($result, [
            'rows' => $all_data,
        ]);
    }

    /**
     * @param $type
     * @return string|void
     */
    public function mappingTable($type)
    {
        switch ($type) {
            case 'IN':
                return "\\App\\Models\\Transactions\\SalesInvoice";
            case 'CN':
                return "\\App\\Models\\Transactions\\SalesNote";
            case 'RC':
                return "\\App\\Models\\Transactions\\SalesPayment";
            case 'BL':
                return "\\App\\Models\\Transactions\\PurchaseInvoice";
            case 'DN':
                return "\\App\\Models\\Transactions\\PurchaseNote";
            case 'PY':
                return "\\App\\Models\\Transactions\\PurchasePayment";

        }
    }

    /**
     * @param $type
     * @return array
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function getForm($type): array
    {
        $form = $this->form('transactions');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['price_include_tax'] = false;
        $form['type'] = $type;
        $form['payment_term_id'] = 1;
        $form['discount_type'] = 'Percent';
        $form['withholding_type'] = 'Percent';
        $form['status'] = 'draft';
        $form['tax_details'] = [];
        $form['items'] = [];
        $form['shipping_fee'] = 0;
        $form['category_id'] = 0;
        $form['parent_id'] = 0;
        $form['id'] = 0;
        $form['account_id'] = 5;
        $form['document_number'] = $this->generateDocNum(Carbon::now(), $type);

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
        $entity = Auth::user()->entity;
        $month = Carbon::now()->format('m');

        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);

        $nextId = \IFRS\Models\Transaction::withTrashed()
                ->where("transaction_type", $alias)
                ->where("transaction_date", ">=", $periodStart)
                ->where("entity_id", '=', $entity->id)
                ->count() + 1;

        return $alias . "-" . str_pad((string)$periodCount, 2, "0", STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 5, "0", STR_PAD_LEFT);
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $request->request->remove('items');
        $request->request->remove('tax_details');
        $request->request->remove('id');
        $request->request->remove('created_at');
        $request->request->remove('updated_at');
        $request->request->remove('deleted_at');
        $request->request->remove('currency');
        $request->request->remove('entity');
        $request->request->remove('parent');
        $request->request->remove('child');

        $data = $request->all();

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['status'] = 'draft';
        }

        return $data;
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
                $item_detail = DocumentItem::find($item['id']);
                $forms = $this->detailsForm($document, $item, 'update');
                foreach ($forms as $index => $form) {
                    $item_detail->$index = $form;
                }
                $item_detail->save();
            } else {
                $item_detail = DocumentItem::create($this->detailsForm($document, $item, 'store'));
            }
            // process tax details
            foreach ($tax_details as $tax_detail) {
                $this->processItemTax($document, $tax_detail, $item_detail);
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
            'account_id' => 45,
            'transaction_id' => $document->id,
            'item_id' => $item['item_id'],
            'name' => $item['name'],
            'narration' => $item['description'],
            'sku' => $item['sku'],
            'quantity' => floatval($item['quantity']),
            'price' => floatval($item['price']),
            'unit' => $item['unit'],
            'tax_name' => $item['tax_name'],
            'tax' => (array_key_exists('tax_name', $item)) ? $this->getTaxIdByName($item['tax_name']) : 0,
            'discount_rate' => floatval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'amount' => floatval($item['total']),
        ];

        $merge = [];
        if ($type == 'store') {
            $merge['created_by'] = auth()->user()->id;
            $form = array_merge($form, $merge);
        }

        return $form;
    }

    /**
     * @param $document
     * @param $tax
     * @param $item_detail
     * @return void
     */
    public function processItemTax($document, $tax, $item_detail)
    {
        if (count($tax) > 0) {
            DocumentItemTax::updateOrCreate(
                [
                    'document_id' => $document->id,
                    'document_item_id' => $item_detail->id,
                ],
                [
                    'entity_id' => $document->entity_id,
                    'type' => $document->type,
                    'document_id' => $document->id,
                    'document_item_id' => $item_detail->id,
                    'tax_id' => $this->getTaxIdByName($tax['name']),
                    'name' => $tax['name'],
                    'amount' => floatval($tax['amount']),
                ]
            );
        }
    }

    /**
     * @param $type
     * @param $parent_id
     * @return string[][]
     */
    public function mappingAction($type, $parent_id): array
    {
        return match ($type) {
            'SQ' => [
                ['title' => 'Sales Order', 'action' => 'SO', 'color' => 'orange', 'button' => true, 'icon' => 'mdi-sale'],
                ['title' => 'Delivery', 'action' => 'SO', 'color' => 'blue', 'button' => false, 'icon' => 'mdi-truck-delivery'],
                ['title' => 'Sales Invoice', 'action' => 'SI', 'color' => 'teal', 'button' => true, 'icon' => 'mdi-receipt'],
                ['title' => 'Incoming Payment', 'action' => 'SI', 'color' => 'green', 'button' => false, 'icon' => 'mdi-currency-usd'],
                ['title' => 'Clone', 'action' => 'SQ', 'color' => 'blue-grey', 'button' => true],
                ['title' => 'Cancel', 'action' => 'C', 'color' => 'red', 'button' => true],
            ],
            'SO' => [
                ['title' => 'Create Invoice', 'action' => 'SI', 'icon' => 'mdi-receipt'],
                ['title' => 'Clone', 'action' => 'SO', 'icon' => 'mdi-content-copy'],
                ['title' => 'Cancel', 'action' => 'C', 'icon' => 'mdi-cancel'],
            ],
            default => [
                ['title' => 'Cancel', 'action' => 'C', 'icon' => 'mdi-cancel'],
            ],
        };
    }

    /**
     * @param $sales_persons
     * @param $document
     * @return void
     */
    public function processSalesPerson($sales_persons, $document)
    {
        if ($sales_persons) {
            foreach ($sales_persons as $sales_person) {
                $user_id = (is_array($sales_person)) ? $sales_person['user_id'] : $sales_person;
                SalesPerson::updateOrCreate([
                    'document_type' => $document->type,
                    'user_id' => $user_id,
                    'document_id' => $document->id,
                ]);
            }
        }
    }


    /**
     * @param $title
     * @param $action
     * @param $parent_id
     * @param $icon
     * @param $color
     * @param $button
     * @return array
     */
    protected function orderAction($title, $action, $parent_id, $icon, $color, $button): array
    {
        $query = Document::where('type', $action)
            ->whereIn('parent_id', (array)$parent_id);

        return [
            'title' => $title,
            'action' => $action,
            'color' => $color,
            'button' => $button,
            'icon' => $icon,
            'items' => $query->get(),
            'pluck' => $query->pluck('id'),
        ];
    }
}
