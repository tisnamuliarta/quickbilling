<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use App\Models\Documents\DocumentItemTax;
use App\Models\Financial\Currency;
use App\Models\Inventory\Contact;
use App\Traits\ApiResponse;
use App\Traits\Financial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentService
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
        $sorts = isset($options->sortBy[0]) ? (string)$options->sortBy[0] : "document_number";
        $order = isset($options->sortDesc[0]) ? (string)$options->sortDesc[0] : "desc";
        $offset = ($pages - 1) * $row_data;

        $result = array();
        $query = Document::select(
            "documents.*",
            DB::raw("'actions' as actions"),
            DB::raw("CONVERT(issued_at, date) as issued_at"),
            DB::raw("CONVERT(due_at, date) as due_at"),
            DB::raw("
                CASE
                    WHEN documents.due_at < DATE(NOW()) AND documents.status <> 'closed' THEN 'overdue'
                    ELSE documents.status
                END as status
            ")
        )
            ->with(['items', 'taxDetails', 'entity'])
            ->where('type', 'LIKE', '%' . $type . '%');

        $result["total"] = $query->count();

        $all_data = $query->orderBy($sorts, $order)
            ->offset($offset)
            ->limit($row_data)
            ->get();

        $result['form'] = $this->getForm($type);
        return array_merge($result, [
            "rows" => $all_data,
        ]);
    }

    /**
     * @param $type
     * @return array
     */
    public function getForm($type): array
    {
        $form = $this->form('documents');
        $form['deposit_info'] = false;
        $form['shipping_info'] = false;
        $form['withholding_info'] = false;
        $form['price_include_tax'] = false;
        $form['type'] = $type;
        $form['issued_at'] = Carbon::now()->format('Y-m-d');
        $form['due_at'] = Carbon::now()->addDay(30)->format('Y-m-d');
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
        $form['document_number'] = $this->generateDocNum(date('Y-m-d H:i:s'), $type);
        $form['temp_id'] = mt_rand(100000, 999999999999);

        return $form;
    }

    /**
     * @param $request
     * @param $type
     * @param null $id
     * @return array
     */
    public function formData($request, $type, $id = null): array
    {
        $request->mergeIfMissing([
            'entity_id' => auth()->user()->entity_id,
        ]);

        $currency = Currency::where('code', $request->default_currency_code)->first();

        $contact = Contact::where('id', $request->contact_id)->first();

        $request->request->remove('tags');
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

        $request->merge([
            'currency_code' => $request->default_currency_code,
            'currency_rate' => $currency->rate,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'contact_tax_number' => $contact->tax_number,
            'contact_phone' => $contact->phone,
            'contact_zip_code' => $contact->zip_code,
            'contact_city' => $contact->city,
        ]);
        $request->request->remove('default_currency_code');
        $request->request->remove('default_currency_symbol');
        $data = $request->all();


        if ($type == 'store') {
            $data['created_by'] = $request->user()->id;
            $data['document_number'] = $this->generateDocNum(date('Y-m-d H:i:s'), $request->type);
            $data['status'] = 'open';
        }

        return $data;
    }

    /**
     * @param $sysDate
     * @param $alias
     *
     * @return string
     */
    protected function generateDocNum($sysDate, $alias): string
    {
        $alias = Str::limit($alias, 2);

        $data_date = strtotime($sysDate);
        $year_val = date('y', $data_date);
        $month = date('m', $data_date);

        $day_val = date('j', $data_date);

        if ((int)$day_val === 1) {
            $document = Str::upper($alias) . '-' . sprintf('%05s', '1');
            $check_document = Document::where('document_number', '=', $document)
                ->where('type', $alias)
                ->first();
            if (!$check_document) {
                return Str::upper($alias) . '-' . (int)$year_val . $month . sprintf('%05s', '1');
            } else {
                //SQ-220100001
                return $this->itemCode($data_date, $alias, $year_val, $month);
            }
        }
        return $this->itemCode($data_date, $alias, $year_val, $month);
    }

    /**
     * @param $data_date
     * @param $alias
     * @param $year_val
     * @param $month
     * @return string
     */
    protected function itemCode($data_date, $alias, $year_val, $month): string
    {
        $full_year = date('Y', $data_date);
        $end_date = date('t', $data_date);

        $first_date = "${full_year}-${month}-01";
        $second_date = "${full_year}-${month}-${end_date}";

        $doc_num = Document::selectRaw('document_number as code')
            ->whereBetween(DB::raw('CONVERT(created_at, date)'), [$first_date, $second_date])
            ->where('type', $alias)
            ->orderBy('code', 'DESC')
            ->first();

        $number = empty($doc_num) ? '0000000000' : $doc_num->code;
        $clear_doc_num = (int)substr($number, 7, 12);
        $number = $clear_doc_num + 1;

        return Str::upper($alias) . '-' . (int)$year_val . $month . sprintf('%05s', $number);
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
            'type' => $document->type,
            'document_id' => $document->id,
            'item_id' => $item['item_id'],
            'name' => $item['name'],
            'description' => $item['description'],
            'sku' => $item['sku'],
            'quantity' => doubleval($item['quantity']),
            'price' => doubleval($item['price']),
            'unit' => $item['unit'],
            'tax_name' => $item['tax_name'],
            'tax' => (array_key_exists('tax_name', $item)) ? $this->getTaxIdByName($item['tax_name']) : 0,
            'discount_rate' => doubleval((array_key_exists('discount_rate', $item)) ? $item['discount_rate'] : 0),
            'total' => doubleval($item['total']),
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
                    'amount' => doubleval($tax['amount'])
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
        switch ($type) {
            case 'SQ':
                //                $order = $this->orderAction('Sales Order', 'SO', $parent_id, 'mdi-sale', 'orange', 'true');
                return [
                    ['title' => 'Sales Order', 'action' => 'SO', 'color' => 'orange', 'button' => true, 'icon' => 'mdi-sale'],
                    ['title' => 'Delivery', 'action' => 'SO', 'color' => 'blue', 'button' => false, 'icon' => 'mdi-truck-delivery'],
                    ['title' => 'Sales Invoice', 'action' => 'SI', 'color' => 'teal', 'button' => true, 'icon' => 'mdi-receipt'],
                    ['title' => 'Incoming Payment', 'action' => 'SI', 'color' => 'green', 'button' => false, 'icon' => 'mdi-currency-usd'],
                    ['title' => 'Clone', 'action' => 'SQ', 'color' => 'blue-grey', 'button' => true],
                    ['title' => 'Cancel', 'action' => 'C', 'color' => 'red', 'button' => true]
                ];
            case 'SO':
                return [
                    ['title' => 'Create Invoice', 'action' => 'SI', 'icon' => 'mdi-receipt'],
                    ['title' => 'Clone', 'action' => 'SO', 'icon' => 'mdi-content-copy'],
                    ['title' => 'Cancel', 'action' => 'C', 'icon' => 'mdi-cancel'],
                ];
            default:
                return [
                    ['title' => 'Cancel', 'action' => 'C', 'icon' => 'mdi-cancel'],
                ];
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
            'pluck' => $query->pluck('id')
        ];
    }
}
