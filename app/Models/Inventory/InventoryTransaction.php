<?php

namespace App\Models\Inventory;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use IFRS\Models\Account;
use IFRS\Models\LineItem;
use IFRS\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'inventory_value',
        'cogs_value'
    ];

    public function getInventoryValueAttribute(): float|int
    {
        return $this->quantity * $this->amount;
    }

    public function getCogsValueAttribute()
    {
        if (Str::contains($this->transaction_type, ['CS', 'IN', 'CN', 'RC'])) {
            return $this->quantity * $this->main_account_amount;
        } else {
            return 0;
        }
    }


    public function transaction()
    {
        if (Str::contains($this->transaction_type, ['CS', 'IN', 'CN', 'RC', 'CP', 'BL', 'DN', 'PY', 'CE', 'JN'])) {
            return $this->belongsTo(Transaction::class);
        } else {
            return $this->belongsTo(Document::class, 'transaction_id');
        }
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function lineItem()
    {
        if (Str::contains($this->transaction_type, ['CS', 'IN', 'CN', 'RC', 'CP', 'BL', 'DN', 'PY', 'CE', 'JN'])) {
            return $this->belongsTo(LineItem::class);
        } else {
            return $this->belongsTo(DocumentItem::class, 'line_item_id');
        }
    }
}
