<?php

namespace App\Traits;

use App\Models\Financial\Account;
use App\Models\Master\Bank;

trait Accounting
{
    /**
     * @param $name
     * @return int
     */
    public function bankIdByName($name): int
    {
        $data = Bank::where('name', $name)->first();
        if ($data) {
            return $data->id;
        }

        return 0;
    }

    /**
     * @param $name
     * @return int
     */
    public function accountByName($name): int
    {
        $data = Account::where('name', $name)->first();
        if ($data) {
            return $data->id;
        }
        return 0;
    }
}
