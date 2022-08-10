<?php

namespace App\Services\Transactions;

use App\Services\Financial\AccountMappingService;
use App\Traits\InventoryHelper;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Transactions\JournalEntry;

class PurchaseService
{
    use InventoryHelper;

    /**
     * @param $line_items
     *
     * @return void
     */
    public function purchaseOrderTransaction($line_items)
    {
        //$item, $ordered_qty, $warehouse
        foreach ($line_items as $line_item) {
            if ($line_item->item->group_name == 'Inventory') {
                $warehouse = $line_item->warehouse_id;
                $ordered_qty = $line_item->quantity;
                $item = $line_item->item_id;

                $item_warehouse = $this->getItemWarehouse($item, $warehouse);

                $item_warehouse->ordered_qty = $item_warehouse->ordered_qty + $ordered_qty;
                $item_warehouse->save();
            }
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function goodsReceiptPurchaseOrderTransaction($document)
    {
        $line_items = $document->lineItems;

        $accountMapping = new AccountMappingService();

        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Allocation Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Inventory Transaction Base On Goods Receipt PO " . $document->transaction_no,
            'credited' => true, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'base_num' => $document->transaction_no,
            'status' => 'open'
        ]);
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_item->inventory_account,
                    'description' => $line_item->item->name,
                    'narration' => $line_item->item->name,
                    'amount' => $line_item->amount,
                    'quantity' => $line_item->quantity,
                    'sub_total' => $line_item->sub_total,
                    'transaction_id' => $journalEntry->id
                ])
            );
        }
        if ($document->status == 'open') {
            $journalEntry->post();
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function supplierBillTransaction($document)
    {
        $line_items = $document->lineItems;
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function debitNoteTransaction($document)
    {
        $line_items = $document->lineItems;
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function goodsReturnTransaction($document)
    {
        $line_items = $document->lineItems;
        $accountMapping = new AccountMappingService();
        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Allocation Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Inventory Transaction  Base On Goods Return " . $document->transaction_no,
            'credited' => false, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'base_id' => $document->id,
            'base_type' => $document->transaction_type,
            'base_num' => $document->transaction_no,
            'status' => 'open'
        ]);
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_item->inventory_account,
                    'description' => $line_item->item->name,
                    'narration' => $line_item->item->name,
                    'amount' => $line_item->amount,
                    'quantity' => $line_item->quantity,
                    'sub_total' => $line_item->sub_total,
                    'transaction_id' => $journalEntry->id
                ])
            );
        }
        if ($document->status == 'open') {
            $journalEntry->post();
        }
    }
}
