<?php

namespace App\Services\Transactions;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItemTax;
use App\Models\Financial\PaymentTerm;
use App\Models\Sales\SalesPerson;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Models\Transaction;
use IFRS\Models\Vat;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService
{
    use ApiResponse;
    use Financial;

    /**
     * @param $request
     * @return array
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function index($request)
    {
        $type = (isset($request->type)) ? $request->type : '';
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'transaction_no';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';

        $model = $this->mappingTable($type);
        $result = [];
        $query = Transaction::with(['entity', 'lineItems', 'contact', 'account.balances', 'ledgers'])
            ->where('transaction_type', $type)
            ->orderBy($sorts, $order)
            ->paginate($row_data);

        $result['form'] = $this->getForm($type);
        $collect = collect($query);
        $result = $collect->merge($result);

        return $result->all();
    }

    /**
     * @param $type
     * @return string|void
     */
    public function mappingTable($type)
    {
        switch ($type) {
            case 'CS':
                return '\\IFRS\\Transactions\\CashSale';
            case 'IN':
                return '\\IFRS\\Transactions\\ClientInvoice';
            case 'CN':
                return '\\IFRS\\Transactions\\CreditNote';
            case 'RC':
                return '\\IFRS\\Transactions\\ClientReceipt';
            case 'BL':
                return '\\IFRS\\Transactions\\SupplierBill';
            case 'DN':
                return '\\IFRS\\Transactions\\DebitNote';
            case 'PY':
                return '\\IFRS\\Transactions\\SupplierPayment';
            case 'CP':
                return '\\IFRS\\Transactions\\CashPurchase';
            case 'CE':
                return '\\IFRS\\Transactions\\ContraEntry';
            case 'JN':
                return '\\IFRS\\Transactions\\JournalEntry';
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
        $payment_term = PaymentTerm::orderBy('id')->first();
        $payment_length = $payment_term->value;

        $form = $this->form('transactions');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['price_include_tax'] = false;
        $form['compound'] = false;
        $form['transaction_type'] = $type;
        $form['type'] = $type;
        $form['payment_term_id'] = 1;
        $form['discount_type'] = 'Percent';
        $form['withholding_type'] = 'Percent';
        $form['transaction_date'] = Carbon::now()->format('Y-m-d');
        $form['due_date'] = Carbon::now()->addDays($payment_length)->format('Y-m-d');
        $form['status'] = 'draft';
        $form['tax_details'] = [];
        $form['line_items'] = [];
        $form['shipping_fee'] = 0;
        $form['category_id'] = 0;
        $form['parent_id'] = 0;
        $form['discount_rate'] = 0;
        $form['deposit'] = 0;
        $form['id'] = 0;
        $form['account_id'] = $this->defaultHeaderAccount($type);
        $form['transaction_no'] = $this->generateDocNum(Carbon::now(), $type);

        if (Str::contains($type, ['CP', 'RC', 'CN', 'BL'])) {
            $form['credited'] = true;
        } else {
            $form['credited'] = false;
        }

        return $form;
    }

    /**
     * @param $type
     * @return int
     */
    protected function defaultHeaderAccount($type): int
    {
        return match ($type) {
            'CP', 'CS' => $this->getAccountIdByName('Cash on hand', 'BANK'),
            'CN', 'RC', 'IN' => $this->getAccountIdByName('Accounts Receivable (A/R)', 'RECEIVABLE'),
            'BL', 'DN', 'PY' => $this->getAccountIdByName('Accounts Payable (A/P)', 'PAYABLE'),
            default => 0,
        };
    }

    /**
     * @param $sysDate
     * @param $alias
     * @return string
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $alias = Str::limit($alias, 2);
        $entity = Auth::user()->entity;
        $month = Carbon::now()->format('m');

        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);

        $nextId = Transaction::withTrashed()
                ->where('transaction_type', $alias)
                ->where('transaction_date', '>=', $periodStart)
                ->where('entity_id', '=', $entity->id)
                ->count() + 1;

        return $alias . '-' . str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $data = $request->all();

        if ($data['action'] == 'saveDraft') {
            $data['status'] = 'draft';
        } else {
            $data['status'] = 'open';
        }
        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
        }

        Arr::forget($data, 'items');
        Arr::forget($data, 'tax_details');
        Arr::forget($data, 'id');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'currency');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'parent');
        Arr::forget($data, 'child');
        Arr::forget($data, 'line_items');
        Arr::forget($data, 'sales_person');
        Arr::forget($data, 'amount');
        Arr::forget($data, 'contact');
        Arr::forget($data, 'ledgers');
        Arr::forget($data, 'date');
        Arr::forget($data, 'vat');
        Arr::forget($data, 'type');
        Arr::forget($data, 'isPosted');
        Arr::forget($data, 'isCredited');
        Arr::forget($data, 'assignable');
        Arr::forget($data, 'clearable');
        Arr::forget($data, 'hasIntegrity');
        Arr::forget($data, 'clearances');
        Arr::forget($data, 'assignments');
        Arr::forget($data, 'withholding_amount');
        Arr::forget($data, 'discount_per_line');
        Arr::forget($data, 'sub_total');
        Arr::forget($data, 'action');
        Arr::forget($data, 'sales_person');
        Arr::forget($data, 'taxDetails');

        $data['narration'] = $data['notes'];

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
            if (!array_key_exists('tax_name', $item)) {
                $item['tax_name'] = null;
            }

            // throw new \Exception($this->detailAccountId($document->transaction_type, $item));
            if (array_key_exists('id', $item) && $item['id']) {
                $item_detail = LineItem::find($item['id']);
                //$line_item[] = $this->detailsForm($document, $item, 'update');
                $forms = $this->detailsForm($document, $item, 'update');
                foreach ($forms as $index => $form) {
                    $item_detail->$index = $form;
                }
                $item_detail->save();
            } else {
                //$line_item[] = $this->detailsForm($document, $item, 'store');
                $item_detail = LineItem::create($this->detailsForm($document, $item, 'store'));
                $vat = Vat::where('id', $item_detail->vat_id)->first();
                if ($vat) {
                    $item_detail->addVat($vat);
                }
            }
            $document->addLineItem($item_detail);
            // process tax details
            foreach ($tax_details as $tax_detail) {
                $this->processItemTax($document, $tax_detail, $item_detail);
            }
        }
        if ($document->status == 'open') {
            $document->post();
        }
    }

    /**
     * @param $document
     * @param $item
     * @param $type
     * @return array
     */
    public function detailsForm($document, $item, $type): array
    {
        $form = [
            'entity_id' => $document->entity_id,
            'account_id' => $this->detailAccountId($document->transaction_type, $item),
            'transaction_id' => $document->id,
            'item_id' => $item['item_id'],
            'narration' => $item['narration'],
            'sku' => $item['unit'],
            'quantity' => floatval($item['quantity']),
            'price' => floatval($item['price']),
            //'unit' => $item['unit'],
            'vat_id' => (Arr::exists($item, 'tax_name')) ? $this->getTaxIdByName($item['tax_name']) : 0,
            'warehouse_id' => (Arr::exists($item, 'whs_name')) ? $this->getWhsIdByName($item['whs_name']) : 0,
            'vat_inclusive' => array_key_exists('tax_name', $item),
            'discount_rate' => floatval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'amount' => floatval($item['amount']),
        ];

        $merge = [];
        if ($type == 'store') {
            $merge['created_by'] = auth()->user()->id;
            $form = array_merge($form, $merge);
        }

        return $form;
    }

    /**
     * @param $type
     * @param $item
     * @return int
     */
    public function detailAccountId($type, $item): int
    {
        if (Str::contains($type, ['CS', 'CN', 'RC', 'IN'])) {
            return $this->getAccountIdItem($item['item_id'], 'sales');
        } else {
            return $this->getAccountIdItem($item['item_id'], 'purchase');
        }
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
                    'type' => $document->transaction_type,
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
                [
                    'title' => 'Sales Order', 'action' => 'SO', 'color' => 'orange',
                    'button' => true, 'icon' => 'mdi-sale',
                ],
                [
                    'title' => 'Delivery', 'action' => 'SO', 'color' => 'blue',
                    'button' => false, 'icon' => 'mdi-truck-delivery',
                ],
                [
                    'title' => 'Sales Invoice', 'action' => 'SI', 'color' => 'teal',
                    'button' => true, 'icon' => 'mdi-receipt',
                ],
                [
                    'title' => 'Incoming Payment', 'action' => 'SI', 'color' => 'green',
                    'button' => false, 'icon' => 'mdi-currency-usd',
                ],
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
                    'document_type' => $document->transaction_type,
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
