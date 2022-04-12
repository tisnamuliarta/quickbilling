<?php

namespace App\Traits;

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
    public function getAccountIdByName($name): int
    {
        $tax = Account::where('name', $name)->first();
        if ($tax) {
            return $tax->id;
        }

        return 0;
    }
}
