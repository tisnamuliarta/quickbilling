<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Services\Transactions\PurchaseService;
use App\Services\Transactions\SalesService;
use App\Services\Transactions\TransactionService;
use App\Traits\InventoryHelper;
use IFRS\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    use InventoryHelper;

    public TransactionService $service;
    public PurchaseService $purchase;
    public SalesService $sales;

    /**
     * MasterUserController constructor.
     *
     * @param TransactionService $service
     * @param PurchaseService $purchase
     * @param SalesService $sales
     */
    public function __construct(TransactionService $service, PurchaseService $purchase, SalesService $sales)
    {
        $this->service = $service;
        $this->purchase = $purchase;
        $this->sales = $sales;
        //    $this->middleware(['direct_permission:Roles-index'])->only(['index', 'show', 'permissionRole']);
        //    $this->middleware(['direct_permission:Roles-store'])->only(['store', 'storePermissionRole']);
        //    $this->middleware(['direct_permission:Roles-edits'])->only('update');
        //    $this->middleware(['direct_permission:Roles-erase'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return $this->success($this->service->index($request));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace(),
            ]);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function groupTransaction(Request $request): JsonResponse
    {
        try {
            $type = (isset($request->typeTrans)) ? $request->typeTrans : '';
            $row_data = isset($request->itemsPerPage) ? (int)$request->itemsPerPage : 10;
            $sorts = isset($request->sortBy[0]) ? (string)$request->sortBy[0] : 'transaction_no';
            $order = isset($request->sortDesc[0]) ? 'DESC' : 'asc';
            $search = (isset($request->search)) ? $request->search : '';
            $date_from = (isset($request->dateFrom)) ? $request->dateFrom : null;
            $date_to = (isset($request->dateTo)) ? $request->dateTo : null;

            $result = [];
            $query = Transaction::with(['entity', 'lineItems', 'contact', 'account.balances', 'ledgers', 'tags'])
                ->whereIn('transaction_type', $type)
                ->where(DB::raw("CONCAT(transaction_no, ' ', narration)"), 'LIKE', '%' . $search . '%')
                ->orderBy($sorts, $order);

            if ($date_from && $date_to) {
                $query = $query->whereBetween('transaction_date', [$date_from, $date_to]);
            }

            $query = $query->paginate($row_data);

            $result['form'] = $this->service->getForm('PY');
            $collect = collect($query);
            $result = $collect->merge($result);

            return $this->success($result->all());
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                $exception->getTrace(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTransactionRequest $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        //$type = (isset($request->type)) ? $request->type : $request->transaction_type;
        $type = $request->transaction_type;
        $model = $this->service->mappingTable($type);
        $items = collect($request->line_items);
        $tax_details = collect($request->tax_details);
        $sales_persons = collect($request->sales_person);

        $bank_account_id = $this->service->getBankAccountId($request);

        // validate details before store
        $validate_details = $this->validateDetails($items, $request->transaction_type, $request->id, '');
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        $tags = $request->tags;

        DB::beginTransaction();
        try {
            if (empty($request->main_account_amount)) {
                throw new \Exception('Transactions must have amount', 1);
            }
            // return $this->error('', 422, $this->service->formData($request, 'store'));
            $document = $model::create($this->service->formData($request, 'store'));

            if ($document->parent_id !== 0) {
                $doc = $model::find($document->parent_id);
                if ($doc) {
                    $doc->status = 'closed';
                    $doc->save();
                }
            }

            $document = $model::find($document->id);

            $this->service->processItems($items, $document, $tax_details, $sales_persons, $bank_account_id);

            $this->service->processSalesPerson($sales_persons, $document);

            // process inventory qty
            $this->processInventory($document);

            if (isset($tags)) {
                $document = Transaction::find($document->id);
                $document->syncTags($tags);
            }

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->type,
            ], 'Data inserted!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    protected function processInventory($document)
    {
        switch ($document->transaction_type) {
            // purchase
            case 'BL':
            case 'CP':
                $this->purchase->supplierBillTransaction($document);
                break;

            case 'DN':
                $this->purchase->debitNoteTransaction($document);
                break;
            // sales
            case 'CS':
                $this->sales->cashSaleTransaction($document);
                break;

            case 'IN':
                $this->sales->clientInvoiceTransaction($document);
                break;

            case 'CN':
                $this->sales->creditNoteTransaction($document);
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $type = $request->type;
            $copy_from_id = $request->copyFromId;

            if (isset($copy_from_id)) {
                if (intval($copy_from_id) != 0) {
                    $id = $copy_from_id;
                }
            }
            $model = $this->service->mappingTable($type);
            $data = Transaction::where('id', $id)
                ->with([
                    'entity',
                    'lineItems.appliedVats',
                    'lineItems.vat',
                    'contact',
                    'salesPerson',
                    'tags',
                    'taxDetails' => function ($query) use ($type) {
                        $query->where('type', '=', $type);
                    },
                    'salesPerson' => function ($query) use ($type) {
                        $query->where('document_type', '=', $type);
                    },
                ])
                ->first();

            return $this->success([
                'data' => $data,
                'form' => $this->service->getForm(($data) ? $data->transaction_type : $type),
                'count' => ($data) ? 1 : 0,
                'action' => ($id != 0) ? $this->service->mappingAction($type, $id) : [],
                'audits' => ($id != 0) ? $data->activities : [],
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreTransactionRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validateRequest($request);

        $type = $request->transaction_type;
        $model = $this->service->mappingTable($type);
        $items = collect($request->line_items);
        $tax_details = collect($request->tax_details);
        $sales_persons = collect($request->sales_person);

        $bank_account_id = $this->service->getBankAccountId($request);

        $action = $request->updateStatus;

        $tags = $request->tags;

        // validate details before update
        $validate_details = $this->validateDetails($items, $request->transaction_type, $request->id, $action);
        if ($validate_details['error']) {
            return $this->error($validate_details['message']);
        }

        try {
            DB::beginTransaction();

            $document = $model::find($id);

            switch ($action) {
                case 'closed':
                case 'canceled':
                    $this->service->updateStatus($id, $action);

                    if ($action == 'canceled') {
                        $this->processInventoryCancel($id);
                    }

                    break;

                default:
                    // Document::where("id", "=", $id)->update($this->service->formData($request, 'update'));
                    $forms = collect($this->service->formData($request, 'update'));
                    //return $this->error('', 422, [$forms]);
                    foreach ($forms as $index => $form) {
                        $document->$index = $form;
                    }
                    $document->save();

                    $this->service->processItems($items, $document, $tax_details, $sales_persons, $bank_account_id);

                    $this->service->processSalesPerson($sales_persons, $document);

                    // process inventory qty
                    $this->processInventory($document);

                    if (isset($tags)) {
                        $document = Transaction::find($document->id);
                        $document->syncTags($tags);
                    }
                    break;
            }

            DB::commit();

            return $this->success([
                'id' => $document->id,
                'status' => 'update',
                'type' => $request->type,
            ], 'Data updated!');
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->error($exception->getMessage(), 422, [
                'errors' => true,
                'Trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * If the transaction type is IN, then call the function processInventoryCancelSales, otherwise if
     * the transaction type is BL, then call the function processInventoryCancelPurchase
     *
     * @param $id
     * The id of the transaction to be cancelled
     */
    public function processInventoryCancel($id)
    {
        $document = Transaction::find($id);
        if ($document->transaction_type == 'IN') {
            $this->processInventoryCancelSales($document);
        }

        if ($document->transaction_type == 'BL') {
            $this->processInventoryCancelPurchase($document);
        }
    }

    protected function processInventoryCancelSales($document)
    {
        foreach($document->lineItems() as $line_item) {
            if ($line_item->item->item_group == 'Inventory') {
                $item_warehouse = $this->getItemWarehouse($line_item->item_id, $line_item->warehouse_id);

                if (!$item_warehouse) {
                    throw new \Exception('Item warehouse not found', 1);
                }
                $quantity = $line_item->quantity;
                $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty + $quantity;
                $item_warehouse->save();
            }
        }
    }

    protected function processInventoryCancelPurchase($document)
    {
        foreach($document->lineItems() as $line_item) {
            if ($line_item->item->item_group == 'Inventory') {
                $item_warehouse = $this->getItemWarehouse($line_item->item_id, $line_item->warehouse_id);

                if (!$item_warehouse) {
                    throw new \Exception('Item warehouse not found', 1);
                }
                $quantity = $line_item->quantity;
                $item_warehouse->on_hand_qty = $item_warehouse->on_hand_qty - $quantity;
                $item_warehouse->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $type = $request->type;
        $model = $this->service->mappingTable($type);
        $document = Transaction::find($id);
        if ($document) {
            $document->delete();

            return $this->success([
                'errors' => false,
            ], 'Row deleted!');
        }

        return $this->error('Row not found', 422, [
            'errors' => true,
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return JsonResponse
     */
    public function getLedger(Request $request, $id): JsonResponse
    {
        $transaction = Transaction::with(['ledgers.postAccount.category', 'ledgers.folioAccount', 'ledgers.lineItem'])
            ->find($id);

        $data = [];
        foreach ($transaction->ledgers as $ledger) {
            $data[] = [
                'amount_credit' => ($ledger->entry_type == 'C') ? $ledger->amount : 0,
                'amount_debit' => ($ledger->entry_type == 'D') ? $ledger->amount : 0,
                'folio_account' => $ledger->folio_account,
                'post_account' => $ledger->postAccount,
                'line_item' => $ledger->line_item,
                'reference' => $ledger->transaction->reference,
                'transaction_type' => $ledger->transaction->type,
                'id' => $ledger->id,
                'rate' => $ledger->rate,
                'posting_date' => $ledger->posting_date,
            ];
        }

        return $this->success([
            'data' => $data,
            'transaction' => $transaction
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getInvoice(Request $request): JsonResponse
    {
        $payment = $request->type;
        $contact = $request->contact;

        $type = '';
        if ($payment == 'RC') {
            $type = 'IN';
        }

        if ($payment == 'PY') {
            $type = 'BL';
        }

        $invoice = DB::table('transactions')
            ->where('transaction_type', $type)
            ->where('contact_id', $contact)
            ->select(
                'transaction_no as classification',
                DB::raw("CONCAT(narration, ' No Invoice: ', transaction_no) as narration"),
                DB::raw('CAST(due_date as DATE) as service_date'),
                'balance_due as sub_total'
            )
            ->get();

        return $this->success([
            'data' => [
                'line_items' => $invoice
            ]
        ]);
    }
}
