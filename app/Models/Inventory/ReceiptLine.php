<?php

namespace App\Models\Inventory;

use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'receipt_items';

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
