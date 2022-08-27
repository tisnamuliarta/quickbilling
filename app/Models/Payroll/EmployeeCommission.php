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
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeCommission extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    protected $appends = [
        'sub_total',
        'employee_name'
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function getSubTotalAttribute(): float|int
    {
        return $this->quantity * $this->amount;
    }

    public function getEmployeeNameAttribute()
    {
        return $this->employee->full_name;
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'transaction_id', 'id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
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
