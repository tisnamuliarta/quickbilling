<?php

namespace App\Traits;

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
}
