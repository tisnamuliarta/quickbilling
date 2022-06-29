<?php

namespace App\Models\Transactions;

use IFRS\Transactions\ContraEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankTransfer extends ContraEntry
{
    use HasFactory;
}
