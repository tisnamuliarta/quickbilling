<?php

namespace App\Models\Payroll;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentItem;
use IFRS\Models\Account;
use IFRS\Models\LineItem;
use IFRS\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCommission extends Model
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

    public function transaction(): BelongsTo
    {
        $inventory = ['IN'];
        if (str($this->transaction_type)->contains($inventory)) {
            return $this->belongsTo(Transaction::class);
        } else {
            return $this->belongsTo(Document::class);
        }
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function lineItem(): BelongsTo
    {
        $inventory = ['IN'];
        if (str($this->transaction_type)->contains($inventory)) {
            return $this->belongsTo(LineItem::class);
        } else {
            return $this->belongsTo(DocumentItem::class);
        }
    }
}
