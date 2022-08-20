<?php

namespace App\Models\Inventory;

use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptLine extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    protected $table = 'receipt_items';

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
