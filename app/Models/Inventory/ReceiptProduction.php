<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceiptProduction extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function lineItems()
    {
        return $this->hasMany(ReceiptLine::class, 'production_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
