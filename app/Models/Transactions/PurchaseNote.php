<?php

namespace App\Models\Transactions;

use IFRS\Transactions\DebitNote;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseNote extends DebitNote
{
    use HasFactory;
}
