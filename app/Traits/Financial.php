<?php

namespace App\Traits;

use App\Models\Inventory\Item;
use App\Models\Inventory\Resource;
use App\Models\Inventory\Warehouse;
use IFRS\Models\Account;
use IFRS\Models\Vat;

trait Financial
{
    /**
     * @param $name
     * @return int
     */
    public function getTaxIdByName($name): int
    {
        $tax = Vat::where('name', $name)->first();
        if ($tax) {
            return $tax->id;
        }

        return 0;
    }

    /**
     * @param $name
     * @return int
     */
    public function getWhsIdByName($name): int
    {
        $tax = Warehouse::where('code', $name)->first();
        if ($tax) {
            return $tax->id;
        }

        return 0;
    }

    /**
     * @param $name
     * @param $type
     * @return int
     */
    public function getAccountIdByName($name, $type): int
    {
        $tax = Account::where('name', $name)
            ->where('account_type', $type)
            ->first();
        if ($tax) {
            return $tax->id;
        }

        return 0;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getResourceById($name): mixed
    {
        return Resource::where('id', $name)
            ->first();
    }

    /**
     * @param $item_id
     * @param $type
     * @return int
     */
    public function getAccountIdItem($item_id, $type): int
    {
        $item = Item::where('id', $item_id)->first();
        if ($type == 'purchase') {
            $item_type = $item->expense_account_id;
        } else {
            $item_type = $item->revenue_account_id;
        }
        $account = Account::where('id', $item_type)
            ->first();
        if ($account) {
            return $account->id;
        }

        return 0;
    }
}
