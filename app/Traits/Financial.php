<?php

namespace App\Traits;

use App\Models\Financial\Account;
use App\Models\Financial\Tax;

trait Financial
{
    /**
     * @param $name
     * @return int
     */
    public function getTaxIdByName($name): int
    {
        $tax = Tax::where('name', $name)->first();
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
