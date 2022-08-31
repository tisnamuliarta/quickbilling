<?php

namespace App\Models\Productions;

use App\Models\Documents\Document;
use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Production extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    protected $appends = [
        'item_name',
        'commission_rate',
        'type',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'main_account_amount' => 'double',
    ];

    /**
     * Instance Type Translator.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return Document::getType($this->transaction_type);
    }

    /**
     * Get Human Readable Transaction type
     *
     * @param string $type
     *
     * @return string
     */
    public static function getType($type): string
    {
        return config('ifrs')['documents'][$type];
    }

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
     * @return HasMany
     */
    public function lineItems(): HasMany
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return mixed
     */
    public function getItemNameAttribute(): mixed
    {
        return ($this->item) ? $this->item->name : null;
    }

    public function getCommissionRateAttribute()
    {
        return ($this->item) ? $this->item->commision_rate : null;
    }
}
