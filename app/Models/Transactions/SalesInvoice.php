<?php

namespace App\Models\Transactions;

use IFRS\Interfaces\Clearable;
use IFRS\Interfaces\Sells;
use IFRS\Traits\Clearing;
use IFRS\Traits\Selling;

class SalesInvoice extends Transaction implements Sells, Clearable
{
    use Selling;
    use Clearing;

    /**
     * Transaction Number prefix
     *
     * @var string
     */

    const PREFIX = Transaction::IN;

    /**
     * Construct new ClientInvoice
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $attributes['credited'] = false;
        $attributes['transaction_type'] = self::PREFIX;

        parent::__construct($attributes);
    }
}
