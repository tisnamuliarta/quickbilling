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
     *
     * @return void
     */
    public function salesOrderTransaction($line_items)
    {
        foreach ($line_items as $line_item) {
            if ($line_item->item->group_name == 'Inventory') {
                $warehouse = $line_item->warehouse_id;
                $quantity = $line_item->quantity;
                $item = $line_item->item_id;

                $item_warehouse = $this->getItemWarehouse($item, $warehouse);

                $item_warehouse->committed_qty = $item_warehouse->committed_qty + $quantity;
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
    public function deliveryTransaction($document)
    {
        $this->createInventoryJournal($document, 'Inventory Transaction Base on Sales delivery ');
    }

    /**
     * @param $document
     * @param $narration
     * @param bool $reverse
     *
     * @return void
     * @throws \Exception
     */
    protected function createInventoryJournal($document, $narration, bool $reverse = false)
    {
        $line_items = $document->lineItems;
        $accountMapping = new AccountMappingService();
        $main_account_amount = 0;
        $count_non_inventory = 0;
        foreach ($line_items as $line_item) {
            if ($line_item->item->item_group == 'Inventory') {
                $count_non_inventory++;
            }
            $quantity = $line_item->quantity;
            $item = $line_item->item_id;
            $warehouse = $line_item->warehouse_id;
            $item_warehouse = $this->getItemWarehouse($item, $warehouse);

            $this->processOnHandQty($line_item, $document);

            $main_account_amount = $main_account_amount + ($item_warehouse->item_cost * $quantity);
        }

        // throw new \Exception($count_non_inventory, 1);
        if ($count_non_inventory != 0) {
            $journalEntry = JournalEntry::create([
                'account_id' => $accountMapping->getAccountByName('Cost of Goods Sold Account')->account_id,
                'date' => Carbon::now(),
                'narration' => $narration . $document->transaction_no,
                'credited' => false, // main account should be debited
                'main_account_amount' => $main_account_amount,
                'reference' => $document->transaction_no,
                'base_id' => $document->id,
                'base_type' => $document->transaction_type,
                'base_num' => $document->transaction_no,
                'created_by' => auth()->user()->id,
                'status' => 'open',
            ]);

            foreach ($line_items as $line_item) {
                $item = $line_item->item_id;
                $warehouse = $line_item->warehouse_id;
                $quantity = $line_item->quantity;

                // get item warehouse 2
                $item_warehouse = $this->getItemWarehouse($item, $warehouse);

                // throw new \Exception('cost :' . $item_warehouse->item_cost . ' qty :' . $quantity, 1);

                $journalEntry->addLineItem(
                    LineItem::create([
                        // 'account_id' => $line_item->inventory_account,
                        'account_id' => $accountMapping->getAccountByName('Inventory Account')->account_id,
                        'description' => $line_item->item->name,
                        'narration' => $line_item->item->name,
                        'amount' => $item_warehouse->item_cost,
                        'quantity' => $quantity,
                        'sub_total' => $line_item->sub_total,
                        'created_by' => auth()->user()->id,
                        'transaction_id' => $journalEntry->id
                    ])
                );
            }
            if ($document->status == 'open' || $document->status == 'paid') {
                $journalEntry->post();
            }
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function clientInvoiceTransaction($document)
    {
        if ($document->base_id) {
            $line_items = $document->lineItems;
            foreach ($line_items as $line_item) {
                $this->processOnHandQty($line_item, $document);
            }
        } else {
            $this->createInventoryJournal($document, 'Inventory Transaction Base On Sales invoice ');
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function cashSaleTransaction($document)
    {
        if ($document->base_id) {
            $line_items = $document->lineItems;
            foreach ($line_items as $line_item) {
                $this->processOnHandQty($line_item, $document);
            }
        } else {
            $this->createInventoryJournal($document, 'Inventory Transaction Base On Cash sale ');
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function creditNoteTransaction($document)
    {
        $this->returnInventoryJournal($document, 'Inventory Transaction Base on Credit note ');

        $line_items = $document->lineItems;
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);
        }
    }

    /**
     * @param $document
     * @param $narration
     *
     * @return void
     * @throws \Exception
     */
    protected function returnInventoryJournal($document, $narration)
    {
        $line_items = $document->lineItems;
        $count_non_inventory = 0;
        foreach ($line_items as $line_item) {
            if ($line_item->item->group_name != 'Inventory') {
                $count_non_inventory++;
            }
        }

        if ($count_non_inventory == 0) {
            $accountMapping = new AccountMappingService();
            $journalEntry = JournalEntry::create([
                'account_id' => $accountMapping->getAccountByName('Inventory Account')->account_id,
                //'account_id' => $accountMapping->getAccountByName('Sales Returns Account')->account_id,
                'date' => Carbon::now(),
                'narration' => $narration . $document->transaction_no,
                'credited' => false, // main account should be debited
                'main_account_amount' => $document->main_account_amount,
                'reference' => $document->transaction_no,
                'base_id' => $document->id,
                'base_type' => $document->transaction_type,
                'base_num' => $document->transaction_no,
                'created_by' => auth()->user()->id,
                'status' => 'open'
            ]);

            foreach ($line_items as $line_item) {
                $this->processOnHandQty($line_item, $document);

                $journalEntry->addLineItem(
                    LineItem::create([
                        'account_id' => $accountMapping->getAccountByName('Cost of Goods Sold Account')->account_id,
                        'description' => $line_item->item->name,
                        'narration' => $line_item->item->name,
                        'amount' => $line_item->amount,
                        'quantity' => $line_item->quantity,
                        'sub_total' => $line_item->sub_total,
                        'transaction_id' => $journalEntry->id,
                        'created_by' => auth()->user()->id,
                    ])
                );
            }
            if ($document->status == 'open' || $document->status == 'paid') {
                $journalEntry->post();
            }
        }
    }

    /**
     * @param $document
     *
     * @return void
     * @throws \Exception
     */
    public function salesReturnTransaction($document)
    {
        $this->returnInventoryJournal($document, 'Inventory Transaction Base on Sales return ');
    }
}
