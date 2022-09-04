<?php

namespace App\Services\Transactions;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItemTax;
use App\Models\Financial\PaymentTerm;
use App\Models\Inventory\Contact;
use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeCommission;
use App\Models\Sales\SalesPerson;
use App\Services\Financial\AccountMappingService;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use IFRS\Models\AppliedVat;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Models\Transaction;
use IFRS\Models\Vat;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    use ApiResponse;
    use Financial;

    /**
     * @param $request
     *
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
        $search = (isset($request->search)) ? $request->search : '';
        $date_from = (isset($request->dateFrom)) ? $request->dateFrom : null;
        $date_to = (isset($request->dateTo)) ? $request->dateTo : null;

        $model = $this->mappingTable($type);
        $result = [];
        $query = Transaction::with(['entity', 'lineItems', 'contact', 'account.balances', 'ledgers'])
            ->where('transaction_type', $type)
            ->where(DB::raw("CONCAT(transaction_no, ' ', narration)"), 'LIKE', '%' . $search . '%')
            ->orderBy($sorts, $order);

        if ($date_from && $date_to) {
            $query = $query->whereBetween('transaction_date', [$date_from, $date_to]);
        }

        $query = $query->paginate($row_data);

        $result['form'] = $this->getForm($type);
        $collect = collect($query);
        $result = $collect->merge($result);

        return $result->all();
    }

    /**
     * @param $type
     *
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
     *
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
        $form['warehouse_id'] = $this->defaultWarehouse()->id;
        $form['warehouse_name'] = $this->defaultWarehouse()->code;
        $form['narration'] = (isset($form['narration'])) ?
            $form['narration'] :
            config('ifrs')['transactions'][$type] . ' ' . $this->generateDocNum(Carbon::now(), $type);

        if (Str::contains($type, ['CP', 'RC', 'CN', 'BL', 'CP'])) {
            $form['credited'] = true;
        } else {
            $form['credited'] = false;
        }

        return $form;
    }

    /**
     * @param $type
     *
     * @return int
     */
    protected function defaultHeaderAccount($type): int
    {
        $accountMapping = new AccountMappingService();
        $bank = $accountMapping->getAccountByName('Cash on Hand')->account_id;
        $revenue = $accountMapping->getAccountByName('Domestic Accounts Receiveable')->account_id;
        $expense = $accountMapping->getAccountByName('Accounts Payable')->account_id;

        return match ($type) {
            'CP', 'CS' => $bank,
            'CN', 'RC', 'IN' => $revenue,
            'BL', 'DN', 'PY' => $expense,
            default => 0,
        };
    }

    /**
     * @param $sysDate
     * @param $alias
     *
     * @return string
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function generateDocNum($sysDate, $alias): string
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
     * @return mixed
     */
    public function defaultWarehouse()
    {
        return Warehouse::first();
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     *
     * @return array
     * @throws \Exception
     */
    public function formData($request, $type, $id = null): array
    {
        $data = $request->all();

        if ($data['action'] == 'saveDraft') {
            $data['status'] = 'draft';
        } else {
            if (Str::contains($data['transaction_type'], ['CS', 'CP', 'PY', 'RC'])) {
                $data['status'] = 'paid';
            } else {
                $data['status'] = 'open';
            }
        }
        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
        }
        $data['discount_amount'] = (isset($data['discount_amount'])) ? $data['discount_amount'] : 0;
        $data['discount_rate'] = (isset($data['discount_rate'])) ? $data['discount_rate'] : 0;
        $data['discount_type'] = (isset($data['discount_type'])) ? $data['discount_type'] : 'Percent';
        $data['balance_due'] = (isset($data['balance_due'])) ? $data['balance_due'] : 0;
        $data['deposit_account_id'] = $this->getBankAccountId($request);

        $contact = Contact::find($data['contact_id']);
        $data['account_id'] = $this->mappingHeaderAccount($data, $contact);

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
        Arr::forget($data, 'action');
        Arr::forget($data, 'sales_person');
        Arr::forget($data, 'taxDetails');
        Arr::forget($data, 'tags');
        Arr::forget($data, 'activities');

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function getBankAccountId($request)
    {
        if (Str::contains($request->transaction_type, ['RC', 'PY'])) {
            if ($request->deposit_account_id == 0) {
                throw new \Exception('Please select deposit account', 1);
            }

            $bank_account_id = (is_array($request->deposit_account_id))
                ? $request->deposit_account_id['id'] : $request->deposit_account_id;
        } else {
            $bank_account_id = 0;
        }
        return $bank_account_id;
    }

    /**
     * @param $data
     * @param $contact
     *
     * @return int
     */
    public function mappingHeaderAccount($data, $contact): int
    {
        $type = $data['transaction_type'];
        $account_id = $data['account_id'];
        if (Str::contains($type, ['IN', 'CN', 'RC'])) {
            return $this->getAccountIdByName($contact->name, 'RECEIVABLE');
        }

        if (Str::contains($type, ['BL', 'DN', 'PY'])) {
            return $this->getAccountIdByName($contact->name, 'PAYABLE');
        }

        if (Str::contains($type, ['CS', 'CP'])) {
            return $account_id;
        }
        return 0;
    }

    /**
     * @param $items
     * @param $document
     * @param $tax_details
     * @param $sales_persons
     * @param $bank_account_id
     *
     * @return void
     * @throws \Exception
     */
    public function processItems($items, $document, $tax_details, $sales_persons, $bank_account_id)
    {
        foreach ($items as $item) {
            if (!array_key_exists('tax_name', $item)) {
                $item['tax_name'] = null;
            }

            if (!Str::contains($document->transaction_type, ['RC', 'PY'])) {
                if (!array_key_exists('amount', $item)) {
                    throw new \Exception('Document line must have amount', 1);
                }
            }

            if (Str::contains($document->transaction_type, ['RC', 'PY'])) {
                if (array_key_exists('amount', $item)) {
                    // only save line item > 0 for receipt client and  Supplier Payment
                    $item_detail = $this->saveLineItem($item, $bank_account_id, $document, $tax_details);

                    if ($document->status == 'paid') {
                        $transaction = Transaction::where('transaction_no', $item_detail->classification)->first();
                        //throw new \Exception($item_detail->classification, 1);
                        $transaction->balance_due = $transaction->balance_due - $item_detail->amount;
                        $transaction->save();

                        $transaction = Transaction::where('transaction_no', $item_detail->classification)->first();
                        if ($transaction->balance_due != 0) {
                            $transaction->status = 'partial';
                            $transaction->save();
                        }

                        if ($transaction->balance_due == 0) {
                            $transaction->status = 'paid';
                            $transaction->save();
                        }
                    }
                }
            } else {
                $item_detail = $this->saveLineItem($item, $bank_account_id, $document, $tax_details);
            }
        }
        if ($document->status == 'open') {
            $document->post();

            if (Str::contains($document->transaction_type, ['IN', 'CS'])) {
                if (count($sales_persons) > 0) {
                    $this->storeEmployeeCommission($sales_persons, $document);
                }
            }
        }
    }

    /**
     * @param $item
     * @param $bank_account_id
     * @param $document
     * @param $tax_details
     *
     * @return mixed
     * @throws \Exception
     */
    protected function saveLineItem($item, $bank_account_id, $document, $tax_details): mixed
    {
        if (array_key_exists('id', $item) && $item['id']) {
            $item_detail = LineItem::find($item['id']);
            //$line_item[] = $this->detailsForm($document, $item, 'update');
            $forms = $this->detailsForm($document, $item, 'update', $bank_account_id);
            foreach ($forms as $index => $form) {
                $item_detail->$index = $form;
            }
            $item_detail->save();
        } else {
            //$line_item[] = $this->detailsForm($document, $item, 'store');
            $item_detail = LineItem::create(
                $this->detailsForm($document, $item, 'store', $bank_account_id)
            );
        }
        if (!Str::contains($document->transaction_type, ['RC', 'PY'])) {
            $vat = Vat::where('id', $item_detail->vat_id)->first();
            if ($vat) {
                $item_detail->addVat($vat);

                $this->appliedVat($item_detail, $vat);
            }
        }

        if (Str::contains($document->transaction_type, ['RC', 'PY'])) {
            $vat = Vat::where('code', 'VAT0')->first();
            if ($vat) {
                $item_detail->addVat($vat);

                $this->appliedVat($item_detail, $vat);
            }
        }

        //throw new \Exception($item_detail->classification, 1);

        $document->addLineItem($item_detail);

        if ($document->status == 'open' || $document->status == 'paid') {
            $document->post();
        }
        // process tax details
        foreach ($tax_details as $tax_detail) {
            $this->processItemTax($document, $tax_detail, $item_detail);
        }

        return $item_detail;
    }

    /**
     * @param $document
     * @param $item
     * @param $type
     * @param $bank_account_id
     *
     * @return array
     * @throws \Exception
     */
    public function detailsForm($document, $item, $type, $bank_account_id): array
    {
        $vat_id = null;
        if (Str::contains($document->transaction_type, ['RC', 'PY'])) {
            $vat = Vat::where('code', 'VAT0')->first();
            $vat_id = $vat?->id;
        } else {
            if (array_key_exists('tax_name', $item)) {
                if ($item['tax_name']) {
                    $vat_id = $this->getTaxIdByName($item['tax_name']);
                }
            }
        }
        $price = (array_key_exists('price', $item)) ? floatval($item['price']) : 1;
        $form = [
            'entity_id' => $document->entity_id,
            'account_id' => $this->detailAccountId($document, $item, $bank_account_id),
            'transaction_id' => $document->id,
            'item_id' => (array_key_exists('item_id', $item)) ? $item['item_id'] : null,
            'narration' => $item['narration'],
            'sku' => array_key_exists('unit', $item) ? $item['unit'] : null,
            'service_date' => array_key_exists('service_date', $item) ? $item['service_date'] : null,
            'check_payment' => array_key_exists('check_payment', $item) ? $item['check_payment'] : false,
            'classification' => array_key_exists('classification', $item) ? $item['classification'] : false,
            'tax_name' => $item['tax_name'],
            'quantity' => (array_key_exists('quantity', $item)) ? floatval($item['quantity']) : 1,
            'price' => $price,
            //'unit' => $item['unit'],
            'vat_id' => $vat_id,
            'warehouse_id' => (Arr::exists($item, 'whs_name')) ? $this->getWhsIdByName($item['whs_name']) : 0,
            //'vat_inclusive' => array_key_exists('tax_name', $item),
            'vat_inclusive' => (Arr::exists($item, 'vat_inclusive')) ? $item['vat_inclusive'] : 0,
            'discount_rate' => floatval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'amount' => (array_key_exists('amount', $item)) ? floatval($item['amount']) : 0,
            'sub_total' => floatval($item['sub_total']),
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
     * @param $item
     * @param $bank_account_id
     *
     * @return int
     */
    public function detailAccountId($document, $item, $bank_account_id): int
    {
        $type = $document->transaction_type;
        if (Str::contains($type, ['CS', 'CN', 'IN'])) {
            return $this->getAccountIdItem($item['item_id'], 'sales');
        } elseif (Str::contains($type, ['RC', 'PY'])) {
            return $bank_account_id;
        } else {
            $accountMapping = new AccountMappingService();
            if (Str::contains($type, ['BL', 'DN'])) {
                if ($document->base_id && $type == 'BL') {
                    return $accountMapping->getAccountByName('Allocation Account')->account_id;
                } else {
                    $item = Item::find($item['item_id']);
                    return $item->inventory_account;
                }
            } elseif (Str::contains($type, ['CP'])) {
                return $this->getAccountIdItem($item['item_id'], 'purchase');
            } else {
                return $this->getAccountIdItem($item['item_id'], 'inventory');
            }
        }
    }

    /**
     * @param $item_detail
     * @param $vat
     *
     * @return void
     */
    protected function appliedVat($item_detail, $vat)
    {
        $itemAmount = $item_detail->amount * $item_detail->quantity;
        $tax = $item_detail->vat_inclusive ?
            $itemAmount - ($itemAmount / (1 + ($vat->rate / 100))) : $itemAmount * $vat->rate / 100;

        AppliedVat::firstOrCreate([
            'vat_id' => $vat->id,
            'line_item_id' => $item_detail->id,
            'amount' => $tax,
        ]);
    }

    /**
     * @param $document
     * @param $tax
     * @param $item_detail
     *
     * @return void
     * @throws \Exception
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
     * @param $sales_persons
     * @param $document
     *
     * @return void
     */
    protected function storeEmployeeCommission($sales_persons, $document)
    {
        $accountMapping = new AccountMappingService();
        $wages_expense = $accountMapping->getAccountByName('Wages Expense Account')->account_id;
        $account_id = $accountMapping->getAccountByName('Payroll Clearing')->account_id;

        $main_account_amount = 0;
        foreach ($document->lineItems as $lineItem) {
            $commission_rate = $lineItem->item->commision_rate;
            $main_account_amount = $main_account_amount + ($lineItem->quantity * $commission_rate);
        }

        $journalEntry = JournalEntry::create([
            'account_id' => $wages_expense,
            'date' => Carbon::now(),
            'narration' => "Sales commission " . $document->contact->name . ' ' . $document->transaction_no,
            'created_by' => auth()->user()->id,
            'credited' => false, // main account should be debited
            'main_account_amount' => $main_account_amount,
            'status' => 'open',
            'base_id' => $document->id,
            'reference' => $document->transaction_no,
            'base_num' => $document->transaction_no,
            'base_type' => $document->transaction_type,
        ]);

        $sum_amount = 0;
        $sum_qty = 0;

        foreach ($document->lineItems as $lineItem) {
            $commission_rate = $lineItem->item->commision_rate;
            //$amount = $commission_rate / count($sales_persons) * $lineItem->quantity;

            foreach ($sales_persons as $sales_person) {
                $user_id = (is_array($sales_person)) ? $sales_person['user_id'] : $sales_person;
                $employee = Employee::find($user_id);

                $commission = EmployeeCommission::create([
                    'employee_id' => $employee->id,
                    'transaction_id' => $document->id,
                    'account_id' => $account_id,
                    'line_item_id' => $lineItem->id,
                    'transaction_type' => $document->transaction_type,
                    'transaction_date' => $document->transaction_date,
                    'quantity' => $lineItem->quantity / count($sales_persons),
                    'amount' => $commission_rate,
                    'status' => 'open'
                ]);

                $sum_amount = $sum_amount + $commission_rate;
                $sum_qty = $sum_qty + $lineItem->quantity / count($sales_persons);
            }

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $account_id,
                    'narration' => 'komisi penjualan ' . $lineItem->item->name . ' ' . $document->transaction_no,
                    'amount' => $commission_rate,
                    'quantity' => $lineItem->quantity,
                    'price' => $commission_rate,
                    'created_by' => auth()->user()->id,
                    'sub_total' => $lineItem->quantity * $commission_rate,
                    //'credited' => false,
                    //'transaction_id' => $journalEntry->id,
                    'item_id' => $lineItem->item_id,
                    'base_line_id' => $lineItem->id
                ])
            );
        }
        $journalEntry->post();
    }

    /**
     * @param $type
     * @param $parent_id
     *
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
     *
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
     * @param $id
     * @param $status
     *
     * @throws \Exception
     */
    public function updateStatus($id, $status)
    {
        $document = Transaction::find($id);
        $document->status = $status;
        $document->save();

        $document->lineItems()->update([
            'status' => $status,
        ]);

        if ($status == 'canceled') {
            // post journal to cancel main document
            $this->processCancelDocument($document);

            // process reference transaction
            $references = Transaction::where('base_id', $document->id)
                ->where('base_type', $document->transacction_type)
                ->get();

            foreach ($references as $reference) {
                $this->processCancelDocument($reference);
            }
        }
    }

    /**
     * @param $document
     *
     * @return void
     */
    public function processCancelDocument($document)
    {
        $line_items = $document->lineItems;
        $journalEntry = JournalEntry::create([
            'account_id' => $document->account_id,
            'date' => Carbon::now(),
            'narration' => 'Cancel transaction no ' . $document->transaction_no,
            'credited' => !(($document->credited == 1)),
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'base_num' => $document->transaction_no,
            'status' => 'closed'
        ]);

        foreach ($line_items as $line_item) {
            // $this->processOnHandQty($line_item, $document);
            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_item->account_id,
                    'description' => $line_item->narration,
                    'narration' => $line_item->narration,
                    'amount' => $line_item->amount,
                    'quantity' => $line_item->quantity,
                    'sub_total' => $line_item->sub_total,
                    'transaction_id' => $journalEntry->id
                ])
            );

            if (count($line_item->appliedVats) > 0) {
                $journalEntry->addLineItem(
                    LineItem::create([
                        'account_id' => $line_item->appliedVats[0]->vat->account_id,
                        'description' => $line_item->narration,
                        'narration' => $line_item->narration,
                        'amount' => $line_item->appliedVats[0]->amount,
                        'quantity' => 1,
                        'sub_total' => $line_item->sub_total,
                        'transaction_id' => $journalEntry->id
                    ])
                );
            }
        }
        $journalEntry->post();
    }

    /**
     * @param $title
     * @param $action
     * @param $parent_id
     * @param $icon
     * @param $color
     * @param $button
     *
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
