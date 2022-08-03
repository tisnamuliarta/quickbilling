<?php

namespace App\Models\Inventory;

use IFRS\Models\Account;
use IFRS\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'sub_total'
    ];

    public function getSubTotalAttribute(): float|int
    {
        return $this->quantity * $this->amount;
    }


    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
