<?php

namespace App\Services\Transactions;

use App\Services\Financial\AccountMappingService;
use App\Traits\InventoryHelper;
use Carbon\Carbon;
use IFRS\Models\LineItem;
use IFRS\Transactions\JournalEntry;

class SalesService
{
    use InventoryHelper;

    /**
     * @param $line_items
     * @return void
     */
    public function salesOrderTransaction($line_items)
    {
        foreach ($line_items as $line_item) {
            $warehouse = $line_item->warehouse_id;
            $quantity = $line_item->quantity;
            $item = $line_item->item_id;

            $item_warehouse = $this->getItemWarehouse($item, $warehouse);

            $item_warehouse->committed_qty = $quantity;
            $item_warehouse->save();
        }
    }

    /**
     * @param $document
     * @return void
     */
    public function deliveryTransaction($document)
    {
        $this->createInventoryJournal($document, 'Sales delivery ');
    }

    /**
     * @param $document
     * @param $narration
     * @param bool $reverse
     * @return void
     */
    protected function createInventoryJournal($document, $narration, bool $reverse = false)
    {
        $line_items = $document->lineItems;
        $accountMapping = new AccountMappingService();

        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Cost of Goods Sold Account')->account_id,
            'date' => Carbon::now(),
            'narration' => $narration . $document->transaction_no,
            'credited' => (bool)$reverse, // main account should be debited
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
                    'credited' => !$reverse,
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
    public function clientInvoiceTransaction($document)
    {
        if ($document->base_id) {
            $line_items = $document->lineItems;
            foreach ($line_items as $line_item) {
                $this->processOnHandQty($line_item, $document);
            }
        } else {
            $this->createInventoryJournal($document, 'Sales invoice ');
        }
    }

    /**
     * @param $document
     * @return void
     */
    public function creditNoteTransaction($document)
    {
        $this->createInventoryJournal($document, 'Credit note ', true);

        $line_items = $document->lineItems;
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);
        }
    }

    /**
     * @param $document
     * @return void
     */
    public function salesReturnTransaction($document)
    {
        $line_items = $document->lineItems;
        $accountMapping = new AccountMappingService();
        $journalEntry = JournalEntry::create([
            'account_id' => $accountMapping->getAccountByName('Sales Returns Account')->account_id,
            'date' => Carbon::now(),
            'narration' => "Sales Return " . $document->transaction_no,
            'credited' => false, // main account should be debited
            'main_account_amount' => $document->main_account_amount,
            'reference' => $document->transaction_no,
            'status' => 'open'
        ]);
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);

            $journalEntry->addLineItem(
                LineItem::create([
                    'account_id' => $accountMapping->getAccountByName('Cost of Goods Sold Account')->account_id,
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
