<?php

namespace App\Models\Transactions;

use IFRS\Transactions\ClientReceipt;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesPayment extends ClientReceipt
{
    use HasFactory;
}
