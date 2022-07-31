<?php

namespace App\Services\Transactions;

use App\Traits\InventoryHelper;

class InventoryService
{
    use InventoryHelper;

    /**
     * @param $document
     * @return void
     * @throws \Exception
     */
    public function goodsIssueTransaction($document)
    {
        $line_items = $document->lineItems;
        //$item, $ordered_qty, $warehouse
        foreach ($line_items as $line_item) {
            $this->processOnHandQty($line_item, $document);
        }
    }
}
