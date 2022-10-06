<?php

namespace App\Models\Documents;

use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use App\Models\Transactions\Classification;
use IFRS\Models\Vat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Scout\Searchable;

class DocumentItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use LogsActivity;

    protected $guarded = [];
    protected $appends = [
        'code',
        'whs_name',
        'default_currency_symbol'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'discount_rate' => 'double',
        'price' => 'double',
        'quantity' => 'double',
        'amount' => 'double',
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

    /**
     * @return BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function vat(): BelongsTo
    {
        return $this->belongsTo(Vat::class);
    }

    /**
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return mixed
     */
    public function getWhsNameAttribute(): mixed
    {
        return ($this->warehouse) ? $this->warehouse->code : null;
    }

    /**
     * @return mixed
     */
    public function getDefaultCurrencySymbolAttribute()
    {
        return auth()->user()->entity->currency->currency_code;
    }

    /**
     * @return mixed
     */
    public function getCodeAttribute(): mixed
    {
        return ($this->item) ? $this->item->code : null;
    }
}
