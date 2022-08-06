<?php

namespace App\Models\Productions;

use App\Models\Inventory\Item;
use App\Models\Inventory\Warehouse;
use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Production extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'item_name',
        'commission_rate'
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
