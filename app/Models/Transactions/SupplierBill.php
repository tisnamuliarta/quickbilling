<?php

namespace App\Models\Transactions;

use IFRS\Interfaces\Buys;
use IFRS\Interfaces\Clearable;
use IFRS\Models\Transaction;
use IFRS\Traits\Buying;
use IFRS\Traits\Clearing;

class SupplierBill extends Transaction implements Buys, Clearable
{
    use Buying;
    use Clearing;

    /**
     * Transaction Number prefix
     *
     * @var string
     */
    const PREFIX = Transaction::BL;

    /**
     * Construct new ContraEntry
     *
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        $attributes['credited'] = true;
        $attributes['test'] = 'true';
        $attributes['transaction_type'] = self::PREFIX;

        parent::__construct($attributes);
    }
}
