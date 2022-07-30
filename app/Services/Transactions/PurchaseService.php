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
     * @return void
     */
    public function purchaseOrderTransaction($line_items)
    {
        //$item, $ordered_qty, $warehouse
        foreach ($line_items as $line_item) {
            $warehouse = $line_item->warehouse_id;
            $ordered_qty = $line_item->quantity;
            $item = $line_item->item_id;

            $item_warehouse = $this->getItemWarehouse($item, $warehouse);

            $item_warehouse->ordered_qty = $ordered_qty;
            $item_warehouse->save();
        }
    }

    /**
     * @param $document
     * @return void
     */
    public function goodsReceiptPurchaseOrderTransaction($document)
    {
        $line_items = $document->lineItems;

        $accountMapping = new AccountMappingService();

        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Allocation Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Goods Receipt PO " . $document->transaction_no,
            'credited' => true, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'status' => 'open'
        ]);
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_item->inventory_account,
                    'description' => $line_item->item->item_name,
                    'narration' => $line_item->item->item_name,
                    'amount' => $line_item->amount,
                    'sub_total' => $line_item->sub_total,
                    'credited' => false,
                    'transaction_id' => $journalEntry->id
                ])
            );
        }
        $journalEntry->post();
    }

    /**
     * @param $document
     * @return void
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
     * @return void
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
     * @return void
     */
    public function goodsReturnTransaction($document)
    {
        $line_items = $document->lineItems;
        $accountMapping = new AccountMappingService();
        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Allocation Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Goods Return " . $document->transaction_no,
            'credited' => false, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'status' => 'open'
        ]);
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $line_item->inventory_account,
                    'description' => $line_item->item->item_name,
                    'narration' => $line_item->item->item_name,
                    'amount' => $line_item->amount,
                    'sub_total' => $line_item->sub_total,
                    'credited' => true,
                    'transaction_id' => $journalEntry->id
                ])
            );
        }
        $journalEntry->post();
    }
}
