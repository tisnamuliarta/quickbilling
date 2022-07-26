<?php

namespace App\Services\Reports;

use App\Models\Inventory\Item;

class ReportService
{
    public function transformProfitAndLoss($data)
    {
        //
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getItemList()
    {
        return Item::with([
            'category',
            'salesAccount',
            'purchaseAccount',
            'inventoryAccounts',
            'salesTax',
            'contact'
        ])
            ->get();
    }
}
