<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use App\Models\Documents\DocumentItemTax;
use App\Models\Financial\PaymentTerm;
use App\Models\Inventory\Contact;
use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use App\Models\Sales\SalesPerson;
use App\Services\Financial\AccountMappingService;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Models\ReportingPeriod;
use IFRS\Transactions\JournalEntry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentService
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
    public function index($request): array
    {
        $type = (isset($request->type)) ? $request->type : '';
        $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
        $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'transaction_no';
        $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
        $search = (isset($request->search)) ? $request->search : '';
        $date_from = (isset($request->dateFrom)) ? $request->dateFrom : null;
        $date_to = (isset($request->dateTo)) ? $request->dateTo : null;

        $result = [];
        $query = Document::select(
            '*',
            DB::raw("
                CASE
                    WHEN documents.due_date < DATE(NOW()) AND documents.status <> 'closed' THEN 'overdue'
                    ELSE documents.status
                END as status
            ")
        )
            ->with(['lineItems', 'taxDetails', 'contact', 'warehouse'])
            ->where('transaction_type', 'LIKE', '%' . $type . '%')
            ->where(DB::raw("CONCAT(transaction_no, ' ', narration)"), 'LIKE', '%' . $search . '%')
            ->orderBy($sorts, $order);

        if ($date_from && $date_to) {
            $query = $query->whereBetween('transaction_date', [$date_from, $date_to]);
        }

        $query = $query->paginate($row_data);

        $collect = collect($query);
        $result['form'] = $this->getForm($type);

        $result = $collect->merge($result);

        return $result->all();
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

        $form = $this->form('documents');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['price_include_tax'] = false;
        $form['transaction_type'] = $type;
        $form['transaction_date'] = Carbon::now()->format('Y-m-d');
        $form['due_date'] = Carbon::now()->addDays($payment_length)->format('Y-m-d');
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
        $form['warehouse_id'] = $this->defaultWarehouse()->id;
        $form['warehouse_name'] = $this->defaultWarehouse()->code;
        $form['narration'] = (isset($form['narration'])) ?
            $form['narration'] :
            config('ifrs')['documents'][$type] . ' ' . $this->generateDocNum(Carbon::now(), $type);

        return $form;
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

        $data_date = strtotime($sysDate);
        $month = date('m', $data_date);

        $entity = Auth::user()->entity;
        $periodCount = ReportingPeriod::getPeriod($sysDate, $entity)->period_count;
        $periodStart = ReportingPeriod::periodStart($sysDate, $entity);
        $periodStart = date('Y-m-d', strtotime($periodStart));

        $nextId = Document::where('transaction_date', '>=', $periodStart)
            ->where('transaction_type', $alias)
            ->count();

        $nextId = $nextId + 1;

        return $alias . '-' . str_pad((string)$periodCount, 2, '0', STR_PAD_LEFT)
            . $month .
            str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return mixed
     */
    public function defaultWarehouse(): mixed
    {
        return Warehouse::first();
    }

    /**
     * @param      $request
     * @param      $type
     * @param null $id
     *
     * @return array
     *
     * @throws \IFRS\Exceptions\MissingReportingPeriod
     */
    public function formData($request, $type, $id = null): array
    {
        $contact = Contact::where('id', $request->contact_id)->first();

        $request->merge([
            'currency_code' => $request->user()->entity->currency->currency_code,
            // 'currency_rate' => $currency->rate,
            'currency_rate' => 0,
            'contact_name' => (isset($contact)) ? $contact->name : '',
            'contact_email' => (isset($contact)) ? $contact->email : '',
            'contact_tax_number' => (isset($contact)) ? $contact->tax_number : '',
            'contact_phone' => (isset($contact)) ? $contact->phone : '',
            'contact_zip_code' => (isset($contact)) ? $contact->zip_code : '',
            'contact_city' => (isset($contact)) ? $contact->city : '',
        ]);
        $data = $request->all();

        $data['contact_id'] = (isset($data['contact_id'])) ? $data['contact_id'] : 0;
        $data['notes'] = (isset($data['footer'])) ? $data['footer'] : 0;

        Arr::forget($data, 'line_items');
        Arr::forget($data, 'contact');
        Arr::forget($data, 'tax_details');
        Arr::forget($data, 'tags');
        Arr::forget($data, 'id');
        Arr::forget($data, 'created_at');
        Arr::forget($data, 'updated_at');
        Arr::forget($data, 'deleted_at');
        Arr::forget($data, 'currency');
        Arr::forget($data, 'entity');
        Arr::forget($data, 'parent');
        Arr::forget($data, 'child');
        Arr::forget($data, 'default_currency_code');
        Arr::forget($data, 'default_currency_symbol');
        Arr::forget($data, 'sales_person');
        Arr::forget($data, 'action');
        Arr::forget($data, 'warehouse_name');
        Arr::forget($data, 'tags');
        Arr::forget($data, 'footer');
        Arr::forget($data, 'type');

        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['transaction_no'] = $this->generateDocNum(date('Y-m-d H:i:s'), $request->transaction_type);
            $data['status'] = 'open';
        }

        return $data;
    }

    /**
     * @param $items
     * @param $document
     * @param $tax_details
     *
     * @return void
     * @throws \Exception
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
            if ($item_detail->vat_id != 0) {
                $this->processItemTax($document, $item_detail);
            }
            if (Str::contains($document->transaction_type, ['GE'])) {
                Item::where('id', $item['item_id'])
                    ->update([
                        'purchase_price' => $item['amount']
                    ]);
            }
        }

        if (Str::contains($document->transaction_type, ['GI', 'GE'])) {
            $transaction_type = $document->transaction_type;
            $accountMapping = new AccountMappingService();
            $account_id = $accountMapping->getAccountByName('Inventory Account')->account_id;
            if ($transaction_type == 'GI') {
                $credited = true;
                $line_account = $accountMapping->getAccountByName('Inventory Offset Decrease Account')->account_id;
            } else {
                $credited = false;
                $line_account = $accountMapping->getAccountByName('Inventory Offset Increase Account')->account_id;
            }
            $this->processIssueReceipt($document, $account_id, $line_account, $credited);
        }
    }

    /**
     * @param $document
     * @param $item
     * @param $type
     *
     * @return array
     * @throws \Exception
     */
    protected function detailsForm($document, $item, $type): array
    {
        $form = [
            'entity_id' => $document->entity_id,
            'type' => $document->transaction_type,
            'document_id' => $document->id,
            'item_id' => $item['item_id'],
            'name' => $item['narration'],
            'narration' => $item['narration'],
            'sku' => (isset($item['code'])) ? $item['code'] : null,
            'quantity' => floatval($item['quantity']),
            'price' => (isset($item['price'])) ? floatval($item['price']) : 0,
            'unit' => $item['unit'],
            'tax_name' => (array_key_exists('tax_name', $item)) ? $item['tax_name'] : null,
            'vat_id' => (array_key_exists('tax_name', $item)) ? $this->getTaxIdByName($item['tax_name']) : 0,
            'warehouse_id' => (array_key_exists('whs_name', $item)) ? $this->getWhsIdByName($item['whs_name']) : 0,
            'discount_rate' => floatval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'amount' => (isset($item['amount'])) ? floatval($item['amount']) : floatval($item['price']),
            'sub_total' => floatval($item['sub_total']),
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

    /**
     * @param $document
     * @param $item_detail
     *
     * @return void
     */
    public function processItemTax($document, $item_detail)
    {
        $amount = $item_detail->vat->rate / 100 * $item_detail->amount;
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
                'tax_id' => $item_detail->vat_id,
                'name' => $item_detail->vat->name,
                'amount' => floatval($amount),
            ]
        );
    }

    /**
     * @param      $document
     * @param      $account_id
     * @param      $line_account
     * @param bool $credited
     *
     * @return void
     */
    public function processIssueReceipt($document, $account_id, $line_account, bool $credited = false)
    {
        $line_items = $document->lineItems;

        $journalEntry = JournalEntry::create([
            'account_id' => $account_id,
            'date' => Carbon::now(),
            'narration' => (isset($document->narration)) ?
                $document->narration : $document->type . ' ' .$document->transaction_no,
            'reference_no' => $document->transaction_no,
            'reference' => $document->transaction_no,
            'credited' => $credited, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'base_num' => $document->transaction_no,
            'status' => 'open',
            'created_by' => auth()->user()->id
        ]);

        foreach ($line_items as $line_item) {
            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_account,
                    'description' => $line_item->item_name,
                    'amount' => $line_item->amount,
                    'quantity' => $line_item->quantity,
                    'item_id' => $line_item->item_id,
                    'unit' => $line_item->unit,
                    'warehouse_id' => $line_item->warehouse_id,
                    //'credited' => true,
                    'transaction_id' => $journalEntry->id,
                    'created_by' => auth()->user()->id
                ])
            );
        }

        if ($document->status == 'open') {
            $journalEntry->post();
        }

        $document = Document::find($document->id);
        $document->transaction_id = $journalEntry->id;
        $document->save();
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
     * @return void
     */
    public function updateStatus($id, $status)
    {
        $document = Document::find($id);
        $document->status = $status;
        $document->save();

        $document->lineItems()->update([
            'status' => $status,
        ]);
    }

    public function updateBaseDocument($document)
    {
        $base_doc = Document::find($document->base_id);

        foreach ($document->lineItems as $item) {
        }
        foreach ($base_doc->lineItems as $base_item) {
        }
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
        $query = Document::where('transaction_type', $action)
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
